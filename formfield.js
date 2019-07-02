const nextbtn = document.getElementById('nextbtn');
const forsingle = document.getElementById('forsingle');
const formultiple = document.getElementById('formultiple');
const submitbtn = document.getElementById('submitbtn');

const t1 = document.getElementById('t1');
const t2 = document.getElementById('t2');
const t3 = document.getElementById('t3');
const t4 = document.getElementById('t4');


forsingle.style.display="none";
formultiple.style.display="none";
submitbtn.style.display="none";

function typeselect() {
    if((t1.checked==true || t2.checked==true || t3.checked==true || t4.checked==true)&& nextbtn.innerHTML=="Next"){
        t1.disabled=true;
        t2.disabled=true;
        t3.disabled=true;
        t4.disabled=true;

        submitbtn.style.display="block";
        nextbtn.innerHTML="Back";
        if(t1.checked==true || t4.checked==true){
            forsingle.style.display="block";
        }
        else if(t3.checked==true || t2.checked==true){
            formultiple.style.display="block";
        }
    }
    else if(nextbtn.innerHTML=="Back"){
        t1.disabled=false;
        t2.disabled=false;
        t3.disabled=false;
        t4.disabled=false;

        forsingle.style.display="none";
        formultiple.style.display="none";
        submitbtn.style.display="none";
        nextbtn.innerHTML="Next";
    }
}

function checkspace(event) {  
   var key = event.keyCode;
   return (key !== 32 && key !== 160 && key != 5760 && key != 8192 && key != 8192 && key != 8194 && key != 8195 && key != 8196 && key != 8197 && key != 8198 && key != 8199 && key != 8200 && key != 8201 && key != 8202 && key != 8232 && key != 8233 && key != 8239 && key != 8287 && key != 12288)
}

var singlename=document.getElementById('singlename');
var multiname=document.getElementById('multiname');

singlename.onkeypress=checkspace;
multiname.onkeypress=checkspace;

nextbtn.onclick=typeselect;
