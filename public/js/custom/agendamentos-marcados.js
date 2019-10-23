var lastResultPaciente;
var medicos = [];
var datasComAgendamento;

function MostraOcultaEventoNoCalendario(elemento) {
    var medicoId = $(elemento).attr('aria-label');
    if ($(elemento).is(':checked')) {
        $('#agendamentos-marcados .medico-' + medicoId).css('visibility', 'visible')
    } else {
        $('#agendamentos-marcados .medico-' + medicoId).css('visibility', 'hidden')
    }
}

function inicializaBusca(className) {
    $(className).select2({
        minimumInputLength: 3,
        language: "pt-BR",
        ajax: {
            url: function () {
                if ($(this).attr('data-url') != undefined) {
                    return $(this).attr('data-url');
                }
                throw 'atribute data-url don\'t exist';
            },
            processResults: function (data) {
                lastResultPaciente = data;
                return {
                    results: data
                }
            },
            dataType: 'json',
            method: 'POST'
        }
    });
}

function dateHasEvent(date) {
    var allEvents = [];
    allEvents = $('#agendamentos-marcados').fullCalendar('clientEvents');
    var event = $.grep(allEvents, function (v) {
        return v.start.format("YYYY-MM-DD") === date;
    });
    return event.length > 0;
}

function exibeAlert(status, message){
    var modal = $('.modal [role="mensagem-modal"]');
    modal.removeClass('alert-success');
    if(status == 'success'){
        modal.addClass('alert-success').removeClass('hidden').html(message);
    }else{
        modal.addClass('alert-danger').removeClass('hidden').html(message)
    }
}


var Evento = function (id, medico, cor) {
    this.title = medico;
    this.id = id;
    this.backgroundColor = cor;
    this.allDay = true;
    this.start = false;
    this.end = false;
    this.eventsDay = [];
    this.horariosCriados;

    this.generateEvents = function (callback) {
        this.eventsDay = [];
        var diaInicial = this.start.clone();

        for (diaInicial; diaInicial.diff(this.end, "days") <= 0; diaInicial.add(1, 'day')) {
            if (diaInicial.format('ddd') != 'Sat' && diaInicial.format('ddd') != 'Sun') {
                var evento = {
                    id: this.id,
                    title: this.title,
                    className: 'medico-' + this.id,
                    backgroundColor: this.backgroundColor,
                    borderColor: this.backgroundColor,
                    allDay: true,
                    start: diaInicial.format('YYYY-MM-DD'),
                    hoursAvaliable: this.horariosCriados,
                }
                this.eventsDay.push(evento);
            }
        }

        if (typeof (callback) != undefined) {
            callback();
        }
    }

    this.generateWeekendEvents = function (callback) {
        this.eventsDay = [];
        var diaInicial = this.start.clone();

        for (diaInicial; diaInicial.diff(this.end, "days") <= 0; diaInicial.add(1, 'day')) {
            if (diaInicial.format('ddd') == 'Mon') {
                diaInicial.add(5, 'day')
            }
            if (diaInicial.format('ddd') == 'Sat' || diaInicial.format('ddd') == 'Sun') {
                var evento = {
                    id: this.id,
                    title: this.title,
                    className: 'medico-' + this.id,
                    backgroundColor: this.backgroundColor,
                    borderColor: this.backgroundColor,
                    allDay: true,
                    start: diaInicial.format('YYYY-MM-DD'),
                    hoursAvaliable: this.horariosCriados,
                }
                this.eventsDay.push(evento);
            }
        }

        if (typeof (callback) != undefined) {
            callback();
        }
    }

};

var Agendamento = function () {
    this.id;

    this.buscaDetalhes = function (id) {
        this.id = id;
        if ($('.loader').is(':hidden')) {
            $('.loader').removeClass('hidden');
            $.ajax({
                url: '/agendamento/buscaDetalhes',
                dataType: 'json',
                method: 'POST',
                global: false,
                data: {
                    id: id
                }
            })
                .done(function (doc) {
                    $('.loader').addClass('hidden');
                    $('#modalAgendamentoDetalhes #paciente').html(doc.paciente.nome);
                    $('#modalAgendamentoDetalhes #medico').html(doc.agenda.medico.nome);
                    var data = moment(doc.dataConsulta.date.substr(0, 19), "YYYY-MM-DD HH:mm:ss");
                    $('#modalAgendamentoDetalhes #data').html(data.format('YYYY-MM-DD'),);
                    $('#modalAgendamentoDetalhes #horario').html(data.format('HH:mm'),);
                    $('#modalAgendamentoDetalhes #status').html(doc.status.nome);
                })
                .fail(function () {
                    $('.loader').addClass('hidden');
                });
        }

    };

    this.confirmarAgendamento = function(){
        if ($('.loader').is(':hidden')) {
            $('.loader').removeClass('hidden');
            this.setStatusAjax('/agendamento/confirmar');
        }
    }

    this.cancelarAgendamento = function(){
        if ($('.loader').is(':hidden')) {
            $('.loader').removeClass('hidden');
            this.setStatusAjax('/agendamento/cancelar');
        }
    }

    this.IniciarAgendamento = function(){
        if ($('.loader').is(':hidden')) {
            $('.loader').removeClass('hidden');
            this.setStatusAjax('/agendamento/iniciar');
        }
    }

    this.setStatusAjax = function(url){
        $.ajax({
            url: url,
            dataType: 'json',
            method: 'POST',
            global: false,
            data: {
                id: this.id
            }
        })
        .done(function (doc) {
            exibeAlert(doc.status, doc.message);
            if(typeof doc.agendaStatus == 'string'){
                doc.agendaStatus = JSON.parse(doc.agendaStatus);
            }
            $('#modalAgendamentoDetalhes #status').html(doc.agendaStatus.nome);
            $('.loader').addClass('hidden');
        })
        .fail(function (doc) {
            exibeAlert(false, doc.message);
            $('.loader').addClass('hidden');
        });
    }
    
}
var agendamento = new Agendamento();

$(document).ready(function () {

    $('#agendamentos-marcados').fullCalendar({
        customButtons: {
            refresh: {
                text: 'Recarregar',
                click: function () {
                    alert('clicked the custom button!');
                }
            }
        },
        header: {
            left: 'title ',
            center: '',
            right: 'today refresh prev,next'
        },
        defaultView: 'listWeek',
        eventAfterAllRender: function () {
            $('.statusMedico').each(function (index, value) {
                MostraOcultaEventoNoCalendario($(value))
            })
        },
        events: function (start, end, timezone, callback) {
            $('.loader').removeClass('hidden');
            $.ajax({
                url: '/agendamento/agendamentos-marcados',
                dataType: 'json',
                method: 'POST',
                data: {
                    start: start.format('YYYY-MM-DD'),
                    end: end.format('YYYY-MM-DD')
                }
            }).done(function (doc) {
                var events = [];
                doc.forEach(function (agendaData) {
                    dataStr = String(agendaData.agendaData.dataConsulta.date);
                    var data = moment(dataStr.substr(0, 19), "YYYY-MM-DD HH:mm:ss");
                    var evento = {
                        id: agendaData.agendaData.id,
                        pacienteId: agendaData.agendaData.pacienteId,
                        title: '[' + agendaData.medicoNome + '] ' + agendaData.pacienteNome,
                        className: 'medico-' + agendaData.medicoId,
                        backgroundColor: agendaData.corAgenda,
                        borderColor: agendaData.corAgenda,
                        allDay: false,
                        start: data.format('YYYY-MM-DD HH:mm'),
                        end: data.clone().add(agendaData.duracaoConsulta, 'm').format('YYYY-MM-DD HH:mm')
                    }
                    events.push(evento);
                });
                $('.loader').addClass('hidden');
                callback(events);
            })
                .fail(function () {
                    $('.loader').addClass('hidden');
                });
        }, //end events

        eventClick: function (calEvent, jsEvent, view) {
            if ($('.loader').is(":hidden")) {
                agendamento.buscaDetalhes(calEvent.id);
                var modalAlert = $('.modal [role="mensagem-modal"]');
                modalAlert.addClass('hidden');
                $('#modalAgendamentoDetalhes').modal('show');
            }
        }
    });

    $('#legenda .statusMedico').change(function () {
        agendamento.paciente = $(this).val();
        MostraOcultaEventoNoCalendario($(this))
    });

    $('body').on('click', '[action="confirmar"]', function () {
        agendamento.confirmarAgendamento();
    });

    $('body').on('click', '[action="cancelar"]', function () {
        agendamento.cancelarAgendamento();
    });

    $('body').on('click', '[action="iniciar"]', function () {
        agendamento.IniciarAgendamento();
    });

    $('body').on('click', '[data-dismiss="modal"]', function () {
        //ocultarAlertas();
    });

    inicializaBusca('#paciente .busca');
    inicializaBusca('#modalAgendamento .busca');
});