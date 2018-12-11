var lastResultPaciente;
var medicos;

function MostraOcultaEventoNoCalendario(elemento) {
    var medicoId = $(elemento).attr('aria-label');
    if ($(elemento).is(':checked')) {
        $('#agendamento .medico-' + medicoId).css('visibility', 'visible')
    } else {
        $('#agendamento .medico-' + medicoId).css('visibility', 'hidden')
    }
}

function inicializaBusca(className) {
    $(className).select2({
        minimumInputLength: 3,
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

function salvarAgendamento(){
    $('#realForm [name="agendamento[paciente]"]').val(agendamento.paciente);
    $('#realForm [name="agendamento[medico]"]').val(agendamento.medico);

    var dataArray = agendamento.data.split('-');
    $('#realForm [name="agendamento[dataConsulta][date][year]"]').val(parseInt(dataArray[0]));
    $('#realForm [name="agendamento[dataConsulta][date][month]"]').val(parseInt(dataArray[1]));
    $('#realForm [name="agendamento[dataConsulta][date][day]"]').val(parseInt(dataArray[2]));
    
    var horaArray = agendamento.horario.split(':');
    $('#realForm [name="agendamento[dataConsulta][time][hour]"]').val(parseInt(horaArray[0]));
    $('#realForm [name="agendamento[dataConsulta][time][minute]"]').val(parseInt(horaArray[1]));
    
    $.ajax({
        url: '/agendamento/new',
        dataType: 'json',
        method: 'POST',
        data: $("#realForm form").serialize()
    }).done(function (doc) {
        console.log(doc);
        
        // $('#modalAgendamento select#horario').html('');
        // var option = new Option();
        // $('#modalAgendamento select#horario').append(option);
        // doc.forEach(function (horario) {
        //     option = new Option(horario,horario);
        //     $('#modalAgendamento select#horario').append(option);
        // });
    });
}

var Evento = function (id, medico, cor) {
    this.title = medico,
        this.id = id,
        this.backgroundColor = cor,
        this.allDay = true,
        this.start = false,
        this.end = false,
        this.eventsDay = [],

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
    this.medico;
    this.paciente;
    this.horario;
    this.data;

    this.montaSelectMedico = function (medicoSelecionado = null) {
        var selectMedico = $('#modalAgendamento #medico');
        selectMedico.html('');
        var option = $('<option>');
        option.text('').clone().appendTo(selectMedico);
        for (index = 0; index < medicos.length; index++) {
            if(medicos[index].id == medicoSelecionado){
                this.medico = medicoSelecionado;
                option.attr('selected',true);
            }else{
                option.attr('selected',false);
            }
            option.val(medicos[index].id).text(medicos[index].nome);
            option.clone().appendTo(selectMedico);
        }
    };

    this.buscaHorariosMedicoSelecionado = function () {
        if(this.medico != undefined && this.paciente != undefined){
            $.ajax({
                url: '/agendamento/horarios-agenda-medico',
                dataType: 'json',
                method: 'POST',
                data: {
                    medicoId: this.medico,
                    data: this.data
                }
            }).done(function (doc) {
                $('#modalAgendamento select#horario').html('');
                var option = new Option();
                $('#modalAgendamento select#horario').append(option);
                doc.forEach(function (horario) {
                    option = new Option(horario,horario);
                    $('#modalAgendamento select#horario').append(option);
                });
            });
        }
    };

    this.pacienteSelecionado = function () {
        var selectPaciente = $('#paciente .buscaPaciente option:selected');
        var selectPacienteModal = $('#modalAgendamento .buscaPaciente');

        selectPacienteModal.val('').html('');

        if (selectPaciente.val() != '') {
            var option = new Option(selectPaciente.text(), selectPaciente.val(), false, false);
            selectPacienteModal.append(option).trigger('change');
            this.buscaHorariosMedicoSelecionado();
        }
    }
}
var agendamento = new Agendamento();

$(document).ready(function () {

    $('#agendamento').fullCalendar({
        eventAfterAllRender: function () {
            $('.statusMedico').each(function (index, value) {
                MostraOcultaEventoNoCalendario($(value))
            })
        },
        events: function (start, end, timezone, callback) {
            $.ajax({
                url: '/agendamento/agendas-medicas',
                dataType: 'json',
                method: 'POST',
                data: {
                    start: start.format('YYYY-MM-DD'),
                    end: end.format('YYYY-MM-DD')
                }
            }).done(function (doc) {
                medicos = doc;
                var events = [];
                doc.forEach(function (medico) {
                    var event = new Evento(medico.id, medico.nome, medico.corAgenda);

                    medico.agenda.forEach(function (agenda) {
                        var arrayData = [
                            agenda.dataInicioAtendimento.date.substring(0, 4),
                            Number(agenda.dataInicioAtendimento.date.substring(5, 7)) - 1,
                            agenda.dataInicioAtendimento.date.substring(8, 10),
                            0, 0, 0
                        ];
                        var dataInicioAtendimento = moment(arrayData);

                        var arrayData = [
                            agenda.dataFimAtendimento.date.substring(0, 4),
                            Number(agenda.dataFimAtendimento.date.substring(5, 7)) - 1,
                            agenda.dataFimAtendimento.date.substring(8, 10),
                            0, 0, 0
                        ];
                        var dataFimAtendimento = moment(arrayData);

                        if (agenda.fimDeSemana) {
                            var eventWeekend = new Evento(medico.id, medico.nome, medico.corAgenda);
                            eventWeekend.start = dataInicioAtendimento;
                            eventWeekend.end = dataFimAtendimento;
                            eventWeekend.generateWeekendEvents(function () {
                                events = events.concat(eventWeekend.eventsDay);
                                eventWeekend = null;
                            });
                        }

                        if (event.start === false) {
                            event.start = dataInicioAtendimento;
                        }

                        if (event.end === false) {
                            event.end = dataFimAtendimento;
                        }

                        if (event.start.diff(dataInicioAtendimento, "days") > 0) {
                            event.start = dataInicioAtendimento;
                        }

                        if (event.end.diff(dataFimAtendimento, "days") < 0) {
                            event.end = dataFimAtendimento;
                        }
                    });

                    event.generateEvents(function () {
                        events = events.concat(event.eventsDay);
                        event = '';
                    });
                });
                callback(events);
            });
        }, //end events
        dayClick: function (date, jsEvent, view) {
            agendamento.data = date.format('YYYY-MM-DD');
            agendamento.montaSelectMedico();
            agendamento.pacienteSelecionado();
            agendamento.buscaHorariosMedicoSelecionado();
            $('#modalAgendamento').modal('show');
        },
        eventClick: function (calEvent, jsEvent, view) {
            agendamento.data = calEvent.start.format('YYYY-MM-DD');
            agendamento.montaSelectMedico(calEvent.id);
            agendamento.pacienteSelecionado();
            agendamento.buscaHorariosMedicoSelecionado();
            $('#modalAgendamento').modal('show');
        }
    });

    $('#legenda .statusMedico').change(function () {
        agendamento.paciente = $(this).val();
        MostraOcultaEventoNoCalendario($(this))
    });

    $('body').on('change','#modalAgendamento select#medico',function () {
        $('#modalAgendamento select#horario').html('');
        var option = new Option();
        $('#modalAgendamento select#horario').append(option);
        agendamento.medico = $(this).val();
        agendamento.buscaHorariosMedicoSelecionado();
    });

    $('body').on('change','#modalAgendamento select#paciente',function () {
        agendamento.paciente = $(this).val();
        agendamento.buscaHorariosMedicoSelecionado();
    });

    $('body').on('change','#modalAgendamento select#horario',function () {
        agendamento.horario = $(this).val();
    });

    $('body').on('click','[action="agendar"]',function(){
        salvarAgendamento();
    });

    inicializaBusca('#paciente .busca');
    inicializaBusca('#modalAgendamento .busca');
});