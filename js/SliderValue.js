window.addEventListener("load", function(){
  var slider = document.getElementById("alert_slider");
  var span = document.getElementById("value");
  slider.addEventListener("change", function(){
    span.innerHTML = this.value;
  })
})
