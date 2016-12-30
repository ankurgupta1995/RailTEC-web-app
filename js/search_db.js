$(document).ready(function(){
  /* get site inputs from DB */
  
  add.get_input_sites();

  var loc = document.getElementById('LocationList');
  var dtfrom = document.getElementById('dateFrom');
  var dtto = document.getElementById('dateTo');

  loc.onchange = function (e) {
      console.log(this.value);
      add.update_columns(this.value);
      add.get_axles(this.value, dtfrom.value, dtto.value);
  };





  //TESTING STUFF-------------
  console.log(dtfrom.value == "");
  console.log(dtto.value == "");
  dtfrom.onchange = function (e) {
      console.log(this.value);
  };
  //--------------------------
});


var add = {

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
      dataType:'json',
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

  get_axles : function(location, datefrom, dateto){
    postData = {'location':location, 'datefrom':datefrom, 'dateto':dateto};
    $.ajax({
      cache:false,
      type:'POST',
      dataType:'json',
      data:postData,
      async:false,
      url:'requestwrapper2.php',
      crossDomain:true,
      success:function(data){
        console.log(data);
      },
      failure:function(){
        alert("Error!!!");
      }
    });
  }

};
