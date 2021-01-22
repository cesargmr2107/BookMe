
var calendar;
var resource_events;

function createCalendar(resource_events){
    resource_events = resource_events;
    var calendarEl = document.getElementById('calendar');
    calendarEl.innerHTML = "";
    calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        allDaySlot : false,
        nowIndicator: true,
        slotMinTime: '05:00:00',
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
            
            // Calculate cost
            var cost = calcAndUpdateCosts(startDate, endDate, startTime, endTime);

            // Add event to hidden input
            var jsonString = document.addForm["INFO_SUBRESERVAS"].value;
            var jsonObject = JSON.parse(jsonString);
            jsonObject["subreservas"][eventId] =  {
                                                    FECHA_INICIO_SUBRESERVA: `${$('#FECHA_INICIO_SUBRESERVA').data('date')}`,
                                                    FECHA_FIN_SUBRESERVA: `${$('#FECHA_FIN_SUBRESERVA').data('date')}`,
                                                    HORA_INICIO_SUBRESERVA: startTime,
                                                    HORA_FIN_SUBRESERVA: endTime
                                                  }
            document.addForm["INFO_SUBRESERVAS"].value = JSON.stringify(jsonObject);          

            // Create new elements for event display
            var divIntervals = document.getElementById('intervals');
            var divInterval = document.createElement('div');
            var divTitle = document.createElement('div');
            divTitle.classList.add("interval-title");
            var icon = document.createElement('span');
            var p = document.createElement('p');
            var ul = document.createElement('ul');
            var li1 = document.createElement('li');
            var li2 = document.createElement('li');

            // Initialize new elements for event display
            divInterval.id = eventId;
            p.innerHTML = `${$('#FECHA_INICIO_SUBRESERVA').data('date')} - ${$('#FECHA_FIN_SUBRESERVA').data('date')}`;
            icon.className = "far fa-times-circle";
            icon.onclick = function(){removeEvent(eventId)};
            li1.innerHTML = `<strong class'i18n-hours'>${translations['i18n-hours']}</strong><span>${startTime} - ${endTime}</span>`;
            li2.innerHTML = `<strong class='i18n-cost'>${translations['i18n-cost']}</strong><span>${cost}</span>`;

            // Append new elements for event display
            ul.appendChild(li1);
            ul.appendChild(li2);
            divTitle.appendChild(p);
            divTitle.appendChild(icon);
            divInterval.appendChild(divTitle);
            divInterval.appendChild(ul);
            divIntervals.appendChild(divInterval);

            // Add event to calendar and move to startdate
            var d1 = new Date (`${startDate}T${startTime}`);
            var d2 = new Date (`${startDate}T${endTime}`);
            var end = new Date (`${endDate}T${startTime}`);
            while(d1 <= end){
                calendar.addEvent({
                    id: eventId,
                    start: d1,
                    end: d2,
                    color: '#4B62BF',
                    extendedProps: {
                        cost: cost
                    }
                });
                d1.setDate(d1.getDate() + 1);
                d2.setDate(d2.getDate() + 1);
            }
            calendar.gotoDate(startDate);
            
            // Add event to list sent from back
            resource_events.push({
                id: eventId,
                startRecur: new Date(startDate),
                endRecur: new Date(endDate),
                startTime: startTime,
                endTime: endTime
            }); 
            
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
        var cost = event.extendedProps["cost"];
        while (event != null){
            event.remove();
            event = calendar.getEventById(eventId);
        }

        // Remove event from list sent from back
        var i = 0;
        while(i < resource_events.length && resource_events[i].id != eventId){
            i++;
        }
        
        resource_events.splice(i, 1);

        // Update total cost
        var totalCost = parseFloat(document.getElementById('totalCost').innerHTML);
        document.getElementById('totalCost').innerHTML = (totalCost - cost).toFixed(2);

    }

    function calcAndUpdateCosts(startDate, endDate, startTime, endTime) {
        
        // Build Date objects
        var startDate = new Date(startDate);
        var endDate = new Date(endDate);
        endDate.setDate(endDate.getDate() + 1);
        var startTime = new Date(`1960-01-01T${startTime}`);
        var endTime = new Date(`1960-01-01T${endTime}`);
        
        // Get resource price and range and calculate new cost
        var price = parseFloat(document.getElementById('TARIFA_RECURSO').innerHTML + ".0");
        var range = document.getElementById('RANGO_TARIFA_RECURSO').innerHTML;

        var factors = {'HORA' : 8.64e+7, 'DIA' : 8.64e+7, 'SEMANA' : 6.048e+8, 'MES': 2.628e+9};
        var numberOf = (endDate - startDate) / factors[range];

        if(range == 'HORA'){
            numberOf *= (endTime - startTime) / (3.6e+6);
        }

        var newCost = numberOf * price;

        // Update total cost
        var totalCost = parseFloat(document.getElementById('totalCost').innerHTML);
        document.getElementById('totalCost').innerHTML = (totalCost + newCost).toFixed(2);
        
        return (numberOf * price).toFixed(2);
    }
}