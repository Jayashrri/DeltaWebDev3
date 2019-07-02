const nextbtn = document.getElementById('nextbtn');
const formultiple = document.getElementById('formultiple');
const submitbtn = document.getElementById('submitbtn');
const forsingle = document.getElementById('forsingle');

var fieldname=document.getElementById('fieldname');

formultiple.style.display="none";
submitbtn.style.display="none";
forsingle.style.display="none";

var radios = document.forms["repeatform"].elements["fieldtype"];
radios[0].onclick = function() {
    formultiple.style.display="none";
    forsingle.style.display="block";
    nextbtn.style.display="none";
    submitbtn.style.display="block";
}
radios[3].onclick = function() {
    formultiple.style.display="none";
    forsingle.style.display="block";
    nextbtn.style.display="none";
    submitbtn.style.display="block";
}
radios[1].onclick = function() {
    formultiple.style.display="none";
    forsingle.style.display="block";
    nextbtn.style.display="block";
    submitbtn.style.display="none";
}
radios[2].onclick = function() {
    formultiple.style.display="none";
    forsingle.style.display="block";
    nextbtn.style.display="block";
    submitbtn.style.display="none";
}

function typeselect() {
    if(nextbtn.innerHTML=="Next"){
        for(i=0;i<4;i++){
            radios[i].readOnly=true;
        }
        fieldname.readOnly=true;
        submitbtn.style.display="block";
        nextbtn.innerHTML="Back";
        formultiple.style.display="block";
    }
    else if(nextbtn.innerHTML=="Back"){
        for(i=0;i<4;i++){
            radios[i].readOnly=false;
        }
        fieldname.readOnly=false;
        forsingle.style.display="none";
        formultiple.style.display="none";
        submitbtn.style.display="none";
        nextbtn.innerHTML="Next";
        nextbtn.style.display="none";
    }
}

function checkspace(event) {  
   var key = event.keyCode;
   return (key !== 32 && key !== 160 && key != 5760 && key != 8192 && key != 8192 && key != 8194 && key != 8195 && key != 8196 && key != 8197 && key != 8198 && key != 8199 && key != 8200 && key != 8201 && key != 8202 && key != 8232 && key != 8233 && key != 8239 && key != 8287 && key != 12288)
}

fieldname.onkeypress=checkspace;
nextbtn.onclick=typeselect;
