
var calendar;
var events;

function createCalendar(resource_events){
    events = resource_events;
    var calendarEl = document.getElementById('calendar');
    calendarEl.innerHTML = "";
    calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        allDaySlot : false,
        nowIndicator: true,
        initialView: 'timeGridWeek',
        slotLabelFormat :{
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        events: resource_events
    });
    calendar.render();
}

function addBooking(){
    
    var startDate = formatDate($('#FECHA_INICIO_SUBRESERVA').data('date'));
    var endDate = formatDate($('#FECHA_FIN_SUBRESERVA').data('date'));
    var startTime = $('#HORA_INICIO_SUBRESERVA').data('date');
    var endTime = $('#HORA_FIN_SUBRESERVA').data('date');
    
    /*console.log(startDate);
    console.log(endDate);
    console.log(startTime);
    console.log(endTime);*/
    
    if( startDate!=undefined && endDate!=undefined &&
        startTime != undefined && endTime != undefined ){

            // Get event id
            var eventNumber = localStorage.getItem('eventNumber');
            if(eventNumber === null){
                localStorage.setItem('eventNumber', 0);
            }else{
                localStorage.setItem('eventNumber', ++eventNumber);
            }
            var eventId = `interval-${eventNumber}`;
            
            // Add event to hidden input
            var jsonString = document.addForm["INFO_SUBRESERVAS"].value;
            var jsonObject = JSON.parse(jsonString);
            jsonObject["subreservas"][eventId] =  {
                                                    FECHA_INICIO_SUBRESERVA: startDate,
                                                    FECHA_FIN_SUBRESERVA: endDate,
                                                    HORA_INICIO_SUBRESERVA: startTime,
                                                    HORA_FIN_SUBRESERVA: endTime,
                                                    COSTE_SUBRESERVA: "5"
                                                  }
            document.addForm["INFO_SUBRESERVAS"].value = JSON.stringify(jsonObject);

            // Create new elements for event display
            var divIntervals = document.getElementById('intervals');
            var divInterval = document.createElement('div');
            var divTitle = document.createElement('div');
            var icon = document.createElement('span');
            var p = document.createElement('p');
            var ul = document.createElement('ul');
            var li1 = document.createElement('li');
            var li2 = document.createElement('li');

            // Initialize new elements for event display
            divInterval.id = eventId;
            p.innerHTML = `${startDate} - ${endDate}`;
            icon.className = "far fa-times-circle";
            icon.onclick = function(){removeEvent(eventId)};
            li1.innerHTML = `<strong>Horas: </strong><span>${startTime} - ${endTime}</span>`;
            li2.innerHTML = `<strong>Tarifa: </strong><span></span>`;

            // Append new elements for event display
            ul.appendChild(li1);
            ul.appendChild(li2);
            divTitle.appendChild(p);
            divTitle.appendChild(icon);
            divInterval.appendChild(divTitle);
            divInterval.appendChild(ul);
            divIntervals.appendChild(divInterval);

            // Add event to calendar
            var d1 = new Date (`${startDate}T${startTime}`);
            var d2 = new Date (`${startDate}T${endTime}`);
            var end = new Date (`${endDate}T${startTime}`);
            while(d1 <= end){
                calendar.addEvent({
                    id: eventId,
                    start: d1,
                    end: d2,
                    color: '#4B62BF'
                });
                d1.setDate(d1.getDate() + 1);
                d2.setDate(d2.getDate() + 1);
            }                                      
            
    }

    function formatDate($dateStr){
        if($dateStr == undefined) {
            return undefined;
        }else{
            var d = $dateStr.split("/");
            return `${d[2]}-${d[1]}-${d[0]}`;
        }
    }

    function removeEvent(eventId){
        
        // Remove from view
        var childToRemove = document.getElementById(eventId);
        divIntervals.removeChild(childToRemove);
        
        // Remove from hidden input
        var jsonString = document.addForm["INFO_SUBRESERVAS"].value;
        var jsonObject = JSON.parse(jsonString);
        delete jsonObject.subreservas[eventId];
        document.addForm["INFO_SUBRESERVAS"].value = JSON.stringify(jsonObject);

        // Remove from calendar
        var event = calendar.getEventById(eventId);
        while (event != null){
            event.remove();
            event = calendar.getEventById(eventId);
        }

    }
}