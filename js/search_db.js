$(document).ready(function(){
  /* get site inputs from DB */
  
  add.get_input_sites();

  document.getElementById('LocationList').onchange = function (e) {
      console.log(this.value);
      add.get_columns(this.value);
  };

  //TESTING STUFF
  var val = document.getElementById('dateFrom').value
  console.log(val);
  document.getElementById('dateFrom').onclick = function (e) {
      console.log(this.value);
  };


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
      failure:function()
      {
        alert("Error!!!");
      }
    });
  },
  get_columns : function(location){
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
        console.log(data);
      },
      failure:function()
      {
        alert("Error!!!");
      }
    });
  }
};
