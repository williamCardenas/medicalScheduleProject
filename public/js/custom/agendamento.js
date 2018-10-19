function MostraOcultaEventoNoCalendario(elemento){
    var medicoId = $(elemento).attr('aria-label');
    if($(elemento).is(':checked')){
        $('#agendamento .medico-'+medicoId).css('visibility', 'visible')
    }else{
        $('#agendamento .medico-'+medicoId).css('visibility', 'hidden')
    }
}

function Evento(id, medico, cor){
    this.title = medico,
    this.id = id,
    this.backgroundColor = cor,
    this.allDay = true,
    this.start = false,
    this.end = false,
    this.eventsDay = [],

    this.generateEvents = function(callback){
        this.eventsDay = [];
        var diaInicial = this.start.clone();
        
        for(diaInicial; diaInicial.diff(this.end, "days") <= 0; diaInicial.add(1, 'day')){
            if(diaInicial.format('ddd') != 'Sat' && diaInicial.format('ddd') != 'Sun'){
                var evento = {
                    title: this.title,
                    className: 'medico-'+this.id,
                    backgroundColor: this.backgroundColor,
                    borderColor: this.backgroundColor,
                    allDay: true,
                    start: diaInicial.format('YYYY-MM-DD'),
                }
               
                this.eventsDay.push(evento);
            }
        }
        
        if(typeof(callback) != undefined){
            callback();
        }
    }

    this.generateWeekendEvents = function(callback){
        this.eventsDay = [];
        var diaInicial = this.start.clone();

        for(diaInicial; diaInicial.diff(this.end, "days") <= 0; diaInicial.add(1, 'day')){
            if(diaInicial.format('ddd') == 'Mon'){
                diaInicial.add(5, 'day')
            }
            if(diaInicial.format('ddd') == 'Sat' || diaInicial.format('ddd') == 'Sun'){
                var evento = {
                    title: this.title,
                    className: 'medico-'+this.id,
                    backgroundColor: this.backgroundColor,
                    borderColor: this.backgroundColor,
                    allDay: true,
                    start: diaInicial.format('YYYY-MM-DD'),
                }
                this.eventsDay.push(evento);
            }
        }
        
        if(typeof(callback) != undefined){
            callback();
        }
    }
};

$('#agendamento').fullCalendar({
    eventAfterAllRender: function(){
        $('.statusMedico').each(function(index, value){
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
            },
            success: function (doc) {
                var events = [];
                doc.forEach(function (medico) {
                    var event = new Evento(medico.id, medico.nome, medico.corAgenda);

                    medico.agenda.forEach(function(agenda){
                        var arrayData = [
                            agenda.dataInicioAtendimento.date.substring(0, 4),
                            Number(agenda.dataInicioAtendimento.date.substring(5, 7))-1,
                            agenda.dataInicioAtendimento.date.substring(8, 10),
                            0,0,0
                        ];
                        var dataInicioAtendimento = moment(arrayData);

                        var arrayData = [
                            agenda.dataFimAtendimento.date.substring(0, 4),
                            Number(agenda.dataFimAtendimento.date.substring(5, 7))-1,
                            agenda.dataFimAtendimento.date.substring(8, 10),
                            0,0,0
                        ];
                        var dataFimAtendimento = moment(arrayData);

                        if(agenda.fimDeSemana){
                            var eventWeekend = new Evento(medico.id, medico.nome, medico.corAgenda);
                            eventWeekend.start = dataInicioAtendimento;
                            eventWeekend.end = dataFimAtendimento;
                            eventWeekend.generateWeekendEvents(function(){
                                events = events.concat(eventWeekend.eventsDay);
                                eventWeekend = null;
                            });    
                        }
                        
                        if(event.start === false){
                            event.start = dataInicioAtendimento;
                        }
                        
                        if(event.end === false){
                            event.end = dataFimAtendimento;
                        }
                        
                        if(event.start.diff(dataInicioAtendimento, "days") > 0){
                            event.start = dataInicioAtendimento;
                        }
                        
                        if(event.end.diff(dataFimAtendimento, "days") < 0){
                            event.end = dataFimAtendimento;
                        }
                    });
                    
                    event.generateEvents(function(){
                        events = events.concat(event.eventsDay);
                        event = '';
                    });
                });
                callback(events);
            }
        });
    }
});

$('.statusMedico').change(function(){
    MostraOcultaEventoNoCalendario($(this))
})