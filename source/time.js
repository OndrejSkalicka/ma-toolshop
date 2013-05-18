function t_deformat(element) {
  tmp = element.innerHTML.split(" ");
  ret = tmp[1]*3600+tmp[3]*60+tmp[5]*1;
  return ret;
}

function t_reformat (time) {
  t_h = Math.floor(time/3600);
  t_m = Math.floor(time/60) % 60;
  t_s = time % 60; 
  if(t_m < 10){t_m = "0" + t_m;} 
  if(t_s < 10){t_s = "0" + t_s;}
  ret = "pøed " + t_h + " h " + t_m + " m " + t_s + " s"
  return ret; 
}

function t_inc () {
  element = document.getElementById("tajna");
  time = null;
  if (element != null) {
    time = t_deformat (element);
    time = t_reformat (time + 1);
    element.innerHTML = time;
  }
  element = document.getElementById("verejna");
  if (element != null) {
    time = t_deformat (element);
    time = t_reformat (time + 1);
    element.innerHTML = time;
  }
  if (time != null)
    setTimeout("t_inc()", 1000);
}
setTimeout("t_inc()", 1000);