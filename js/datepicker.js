
      $( function() {
        var dateFormat = "yy/mm/dd",
          from = $( "#checkin" )
            .datepicker({
              defaultDate: "+1w", 
              changeMonth: true,
              minDate: 0,
              numberOfMonths: 2,
              dateFormat: "yy/mm/dd",
            })
            .on( "change", function() {
              to.datepicker( "option", "minDate", getDate( this ) );
            }),
          to = $( "#checkout" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 2,
            dateFormat: "yy/mm/dd",
          })
          .on( "change", function() {
            from.datepicker( "option", "maxDate", getDate( this ) );
          });
    
        function getDate( element ) {
          var date;
          try {
            date = $.datepicker.parseDate( dateFormat, element.value );
          } catch( error ) {
            date = null;
          }
    
          return date;
        }
      } );
      

      
      $( function() {
        var dateFormat = "yy/mm/dd",
          from = $( "#from" )
            .datepicker({
              defaultDate: "+1w",
              changeMonth: true,
              numberOfMonths: 2,
              dateFormat: "yy/mm/dd",
            })
            .on( "change", function() {
              to.datepicker( "option", "minDate", getDate( this ) );
            }),
          to = $( "#to" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 2,
            
            dateFormat: "yy/mm/dd",
          })
          .on( "change", function() {
            from.datepicker( "option", "maxDate", getDate( this ) );
          });
    
        function getDate( element ) {
          var date;
          try {
            date = $.datepicker.parseDate( dateFormat, element.value );
          } catch( error ) {
            date = null;
          }
    
          return date;
        }
      } );

     