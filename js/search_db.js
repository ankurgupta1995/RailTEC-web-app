$(document).ready(function(){
  /* get site inputs from DB */
  
  //----------------------------Init the plugins and input modifiers---------------------------
  add.get_input_sites();
  //limit date picker according to site, direction
  add.make_date_picker();
  //limit time picker according to site and date and direction
  add.make_time_picker();
  //axle affected by location, direction, date, time, temperature, speed.
  add.make_axle_slider();
  //temperature affected by location, direction, date, time
  add.make_temp_slider();
  //speed affected by location, direction, date, time, temperature
  add.make_speed_slider();
  //-------------------------------------------------------------------------------------------

  //----------------------------------------INIT VALUES IN LABELS-----------------------------------------------------
  $( "#axles" ).val($( "#axle-slider" ).slider( "values", 0 ) + " - " + $( "#axle-slider" ).slider( "values", 1 ) );
  $( "#temperature" ).val($( "#temp-slider" ).slider( "values", 0 ) + " - " + $( "#temp-slider" ).slider( "values", 1 ) );
  $( "#speed" ).val($( "#speed-slider" ).slider( "values", 0 ) + " - " + $( "#speed-slider" ).slider( "values", 1 ) );
  //--------------------------------------------------------------------------------------------------------------------

  var loc = document.getElementById('LocationList');
  var dtfrom = document.getElementById('dateFrom');
  var dtto = document.getElementById('dateTo');
  var dir = document.getElementById('Direction');
  var timefrom = document.getElementById('timeFrom');
  var timeto = document.getElementById('timeTo');
  var axle = document.getElementById('axle-slider');
  var speed = document.getElementById('speed-slider');
  var temp = document.getElementById('temp-slider');


  add.update_columns(loc.value);
  add.get_axles(loc.value, dtfrom.value, dtto.value);

  loc.onchange = function (e) {
      console.log(this.value);
      add.update_columns(this.value);
      add.get_axles(this.value, dtfrom.value, dtto.value);
  };

  dtfrom.onchange = function(e){
    console.log(this.value);
  }

  dtto.onchange = function(e){
    console.log(this.value);
  }


  //figure this out
  dir.onchange = function(e){
    console.log(this.value);
  }

  timefrom.onchange = function(e){
    console.log(this.value);
  }

  //sliders do not have onchange.





  //TESTING STUFF-------------
  console.log(dtfrom.value == "");
  console.log(dtto.value == "");
  console.log(timefrom.value == "");
  console.log(timeto.value == "");
  console.log(typeof(dtfrom.value));
  console.log(typeof(dtto.value));
  console.log(typeof(timefrom.value));
  console.log(typeof(timeto.value));
  //--------------------------
});


var add = {

  make_time_picker : function(){
    var starttime = $("#timeFrom");
    var endtime = $("#timeTo");
    $.timepicker.timeRange(starttime, 
                            endtime, 
                          {
                            minInterval:0, 
                            timeFormat: 'HH:mm:ss',
                            start:{
                              timeInput:true,
                            },
                            end:{
                              timeInput:true,
                            }
                          }
                          );
  },

  make_date_picker : function(){
    var dateFormat = "yy-mm-dd";
      from = $( "#dateFrom" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          changeYear: true,
          numberOfMonths: 1
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", add.getDate( this ) );
        }),
      to = $( "#dateTo" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", add.getDate( this ) );
      });
    },

    getDate : function( element ) {
      var date;
      var dateFormat = "yy-mm-dd";
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
      return date;
    },

  make_speed_slider : function(){
    $('#speed-slider').slider({
      range:true,
      step:0.01,
      slide:function(event, ui){
        $('#speed').val( "$" + ui.values[0] + " - $" + ui.values[1]);
        document.getElementById("speed").value = ui.values[0] + " - " + ui.values[1];
      }
    });
  },

  make_temp_slider : function(){
    $('#temp-slider').slider({
      range:true,
      step:0.01,
      slide:function(event, ui){
        $('#temperature').val( "$" + ui.values[0] + " - $" + ui.values[1]);
        document.getElementById("temperature").value = ui.values[0] + " - " + ui.values[1];
      }
    });
  },

  make_axle_slider : function(){
    $('#axle-slider').slider({
      range:true,
      min:0.00,
      step:1,
      slide:function(event, ui){
        $('#axles').val( "$" + ui.values[0] + " - $" + ui.values[1]);
        document.getElementById("axles").value = ui.values[0] + " - " + ui.values[1];
        console.log(ui.values[0]);
        console.log(ui.values[1]);
      }
    });
  },

  get_input_sites : function(){
    $.ajax({
      cache:false,
      type:'GET',
      async:false,
      url:'requestwrapper.php',
      crossDomain:true,
      success:function(data){
        var sel = document.getElementById('LocationList');
        var opt = document.createElement('option');
        var input_sites = JSON.parse(data);
        for(var i = 0; i < input_sites.length; i++) {
          opt = document.createElement('option');
          opt.innerHTML = input_sites[i];
          opt.value = input_sites[i];
          sel.appendChild(opt);
        }
      },
      failure:function(){
        alert("Error!!!");
      }
    });
  },

  update_columns : function(location){
    postData = {'location':location};
    $.ajax({
      cache:false,
      type:'POST',
      dataType:'JSON',
      data:postData,
      async:false,
      url:'requestwrapper.php',
      crossDomain:true,
      success:function(data){
        var col_names = data;
        var cols = document.getElementById('columnlist');
        cols.innerHTML = "";
        var main_label = document.createElement("label");
        main_label.setAttribute("for", "columnlist");
        main_label.setAttribute("class", "col-md-4 control-label");
        main_label.innerHTML = "Select Column(s)";
        cols.appendChild(main_label);
        for(var option = 0; option < col_names.length; option++)
        {
          cols.appendChild(document.createElement("br"));
          var checkbox = document.createElement("input");
          checkbox.type = "checkbox";
          checkbox.name = "collist[]";
          checkbox.value = col_names[option];
          cols.appendChild(checkbox);


          var label = document.createElement("label");
          label.setAttribute("for", col_names[option]);
          label.innerHTML = col_names[option];
          cols.appendChild(label);
        }
      },
      failure:function(){
        alert("Error!!!");
      }
    });
  },

// add direction, min and max speed, min and max temp
  get_axles : function(location, datefrom, dateto){
    location = JSON.stringify(location);
    datefrom = JSON.stringify(datefrom);
    dateto = JSON.stringify(dateto);
    postData = {'location':location, 'datefrom':datefrom, 'dateto':dateto};
    $.ajax({
      cache:false,
      type:'POST',
      data:postData,
      async:false,
      url:'requestwrapper2.php',
      crossDomain:true,
      success:function(data){
        var new_max = parseInt(data.replace(/^"|"$/g, ""));
        if(isNaN(new_max))
          alert("new max is not a number");
        else
        {
          $("#axle-slider").slider("option", "max", new_max);
        }
      },
      failure:function(){
        alert("Error!!!");
      }
    });
  }

};
