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

  //----------------------------------------INIT SLIDER VALUES IN LABELS-----------------------------------------------------
  //DO THIS AFTER SETTING MIN AND MAX EVERY TIME----------------------------------------------------------------
  var axle_vals = [$("#axle-slider").slider("option", "min"), $("#axle-slider").slider("option", "max")];
  $("#axle-slider").slider("option", "values", axle_vals);
  var speed_vals = [$("#speed-slider").slider("option", "min"), $("#speed-slider").slider("option", "max")];
  $("#speed-slider").slider("option", "values", speed_vals);
  var temp_vals = [$("#temp-slider").slider("option", "min"), $("#temp-slider").slider("option", "max")];
  $("#temp-slider").slider("option", "values", temp_vals);
  //-----------------------------------------------------------------------------------------------------------------
  $( "#axles" ).val($( "#axle-slider" ).slider( "values", 0 ) + " - " + $( "#axle-slider" ).slider( "values", 1 ) );
  $( "#temperature" ).val($( "#temp-slider" ).slider( "values", 0 ) + " - " + $( "#temp-slider" ).slider( "values", 1 ) );
  $( "#speed" ).val($( "#speed-slider" ).slider( "values", 0 ) + " - " + $( "#speed-slider" ).slider( "values", 1 ) );
  //--------------------------------------------------------------------------------------------------------------------


  var loc = document.getElementById('LocationList');
  var dtfrom = document.getElementById('dateFrom');
  var dtto = document.getElementById('dateTo');
  var dir = document.getElementById('DirectionList');
  var timefrom = document.getElementById('timeFrom');
  var timeto = document.getElementById('timeTo');
  var axle = $('#axle-slider');
  var speed = $('#speed-slider');
  var temp = $('#temp-slider');


  add.update_columns(loc.value);

  //DONE
  loc.onchange = function (e) {
      console.log(this.value);
      add.update_columns(this.value);
      add.get_dates(this.value, dir.value);
      add.get_time(this.value, dir.value, dtfrom.value, dtto.value);
      add.get_temp(this.value, dir.value, dtfrom.value, dtto.value, timefrom.value, timeto.value);
      add.get_speed(this.value, dir.value, dtfrom.value, dtto.value, timefrom.value, timeto.value, 
                    temp.slider("option", "values")[0], temp.slider("option", "values")[1]);
      add.get_axles(this.value, dtfrom.value, dtto.value, dir.value, timefrom.value, timeto.value, 
                    temp.slider("option", "values")[0], temp.slider("option", "values")[1],
                    speed.slider("option", "values")[0], speed.slider("option", "values")[1]);
  };

  //DONE
  dtfrom.onchange = function(e){
    console.log(loc.value);
    add.get_time(loc.value, dir.value, this.value, dtto.value);
    add.get_temp(loc.value, dir.value, this.value, dtto.value, timefrom.value, timeto.value);
    add.get_speed(loc.value, dir.value, this.value, dtto.value, timefrom.value, timeto.value, 
                  temp.slider("option", "values")[0], temp.slider("option", "values")[1]);
    add.get_axles(loc.value, this.value, dtto.value, dir.value, timefrom.value, timeto.value, 
                  temp.slider("option", "values")[0], temp.slider("option", "values")[1],
                  speed.slider("option", "values")[0], speed.slider("option", "values")[1]);
  }

  //DONE
  dtto.onchange = function(e){
    console.log(this.value);
    add.get_time(loc.value, dir.value, dtfrom.value, this.value);
    add.get_temp(loc.value, dir.value, dtfrom.value, this.value, timefrom.value, timeto.value);
    add.get_speed(loc.value, dir.value, dtfrom.value, this.value, timefrom.value, timeto.value, 
                  temp.slider("option", "values")[0], temp.slider("option", "values")[1]);
    add.get_axles(loc.value, dtfrom.value, this.value, dir.value, timefrom.value, timeto.value, 
                  temp.slider("option", "values")[0], temp.slider("option", "values")[1],
                  speed.slider("option", "values")[0], speed.slider("option", "values")[1]);
  }

  //DONE
  dir.onchange = function(e){
    console.log(this.value);
    add.get_dates(loc.value, this.value);
    add.get_time(loc.value, this.value, dtfrom.value, dtto.value);
    add.get_temp(loc.value, this.value, dtfrom.value, dtto.value, timefrom.value, timeto.value);
    add.get_speed(loc.value, this.value, dtfrom.value, dtto.value, timefrom.value, timeto.value, 
                  temp.slider("option", "values")[0], temp.slider("option", "values")[1]);
    add.get_axles(loc.value, dtfrom.value, dtto.value, this.value, timefrom.value, timeto.value, 
                  temp.slider("option", "values")[0], temp.slider("option", "values")[1],
                  speed.slider("option", "values")[0], speed.slider("option", "values")[1]);
  }

  //DONE
  timefrom.onchange = function(e){
    console.log(this.value);
    add.get_temp(loc.value, dir.value, dtfrom.value, dtto.value, this.value, timeto.value);
    add.get_speed(loc.value, dir.value, dtfrom.value, dtto.value, this.value, timeto.value, 
                  temp.slider("option", "values")[0], temp.slider("option", "values")[1]);
    add.get_axles(loc.value, dtfrom.value, dtto.value, dir.value, this.value, timeto.value, 
                  temp.slider("option", "values")[0], temp.slider("option", "values")[1],
                  speed.slider("option", "values")[0], speed.slider("option", "values")[1]);
  }

  //DONE
  timeto.onchange = function(e){
    console.log(this.value);
    add.get_temp(loc.value, dir.value, dtfrom.value, dtto.value, timefrom.value, this.value);
    add.get_speed(loc.value, dir.value, dtfrom.value, dtto.value, timefrom.value, this.value, 
                  temp.slider("option", "values")[0], temp.slider("option", "values")[1]);
    add.get_axles(loc.value, dtfrom.value, dtto.value, dir.value, timefrom.value, this.value, 
                  temp.slider("option", "values")[0], temp.slider("option", "values")[1],
                  speed.slider("option", "values")[0], speed.slider("option", "values")[1]);
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
    var dateFormat = "mm/dd/yy";
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
      var dateFormat = "mm/dd/yy";
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
      return date;
    },

  //set values to min and max for all sliders in init.
  make_speed_slider : function(){
    var loc = document.getElementById('LocationList');
    var dtfrom = document.getElementById('dateFrom');
    var dtto = document.getElementById('dateTo');
    var dir = document.getElementById('DirectionList');
    var timefrom = document.getElementById('timeFrom');
    var timeto = document.getElementById('timeTo');
    var temp = $('#temp-slider');
    $('#speed-slider').slider({
      range:true,
      step:0.01,
      slide:function(event, ui){
        $('#speed').val( "$" + ui.values[0] + " - $" + ui.values[1]);
        document.getElementById("speed").value = ui.values[0] + " - " + ui.values[1];
        add.get_axles(loc.value, dtfrom.value, dtto.value, dir.value, timefrom.value, this.value, 
                      temp.slider("option", "values")[0], temp.slider("option", "values")[1],
                      ui.values[0], ui.values[1]);
      }
    });
  },

  make_temp_slider : function(){
    var loc = document.getElementById('LocationList');
    var dtfrom = document.getElementById('dateFrom');
    var dtto = document.getElementById('dateTo');
    var dir = document.getElementById('DirectionList');
    var timefrom = document.getElementById('timeFrom');
    var timeto = document.getElementById('timeTo');
    var speed = $('#speed-slider');
    var temp = $('#temp-slider');
    $('#temp-slider').slider({
      range:true,
      step:0.01,
      slide:function(event, ui){
        $('#temperature').val( "$" + ui.values[0] + " - $" + ui.values[1]);
        document.getElementById("temperature").value = ui.values[0] + " - " + ui.values[1];
        add.get_speed(loc.value, dir.value, dtfrom.value, dtto.value, timefrom.value, timeto.value, 
                  ui.values[0], ui.values[1]);
        add.get_axles(loc.value, dtfrom.value, dtto.value, dir.value, timefrom.value, timeto.value, 
                  ui.values[0], ui.values[1], speed.slider("option", "values")[0], speed.slider("option", "values")[1]);
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
        console.log(typeof(ui.values[0]));
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
  get_axles : function(location, datefrom, dateto, direction, timefrom, timeto, tempfrom, tempto, speedfrom, speedto){
    postData = {'location' : location, 'dir':direction, 'datefrom':datefrom, 'dateto':dateto, 'timefrom':timefrom, 'timeto':timeto,
                'tempfrom':tempfrom, 'tempto':tempto, 'speedfrom':speedfrom, 'speedto':speedto, 'reqType':"getaxle"};
    $.ajax({
      cache:false,
      type:'POST',
      data:postData,
      async:false,
      url:'requestwrapper2.php',
      crossDomain:true,
/*      success:function(data){
        var new_max = parseInt(data.replace(/^"|"$/g, ""));
        if(isNaN(new_max))
          alert("new max is not a number");
        else
        {
          $("#axle-slider").slider("option", "max", new_max);
        }
      },*/
      success:function(data){
        console.log(data);
      },
      failure:function(){
        alert("Error!!!");
      }
    });
  },

  get_dates : function(location, direction){
    //make a keyword mapping to differentiate between different post calls.
    postData = {'location' : location, 'dir':direction, 'reqType' : "getdates"};
    $.ajax({
      cache:false,
      type:'POST',
      data:postData,
      async:false,
      url: 'requestwrapper2.php',
      crossDomain: true,
      success:function(data){
        console.log(data);
      },
      failure:function(){
        alert("could not get the min and max dates!");
      }
    });
  },

  get_time : function(location, direction, datefrom, dateto){
    postData = {'location' : location, 'dir':direction, 'datefrom':datefrom, 'dateto':dateto, 'reqType':"gettime"};
    $.ajax({
      cache:false,
      type:'POST',
      data:postData,
      async:false,
      url:'requestwrapper2.php',
      crossDomain:true,
      success:function(data){
        console.log(data);
      },
      failure:function(){
        alert("could not get the min and max times!");
      }
    });
  },

  get_temp : function(location, direction, datefrom, dateto, timefrom, timeto){
    postData = {'location' : location, 'dir':direction, 'datefrom':datefrom, 'dateto':dateto, 'timefrom':timefrom, 'timeto':timeto, 'reqType':"gettemp"};
    $.ajax({
      cache:false,
      type:'POST',
      data:postData,
      async:false,
      url:'requestwrapper2.php',
      crossDomain:true,
      success:function(data){
        console.log(data);
      },
      failure:function(){
        alert("could not get the min and max temps!");
      }
    });
  },

  get_speed : function(location, direction, datefrom, dateto, timefrom, timeto, tempfrom, tempto){
    postData = {'location' : location, 'dir':direction, 'datefrom':datefrom, 'dateto':dateto, 'timefrom':timefrom, 'timeto':timeto,
                'tempfrom':tempfrom, 'tempto':tempto, 'reqType':"getspeed"};
    $.ajax({
      cache:false,
      type:'POST',
      data:postData,
      async:false,
      url:'requestwrapper2.php',
      crossDomain:true,
      success:function(data){
        console.log(data);
      },
      failure:function(){
        alert("could not get the min and max speeds!");
      }
    });
  }

};
