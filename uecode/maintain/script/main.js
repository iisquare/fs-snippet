// JavaScript Document
$(document).ready(function(){
	var Marquee1 = new Marquee("MScroll");
	Marquee1.Direction = "top";
	Marquee1.Step = 1;
	//Marquee1.Width = 400;
	Marquee1.Height = 25;
	Marquee1.Timer = 50;
	Marquee1.DelayTime = 5000;
	Marquee1.WaitTime = 3000;
	Marquee1.ScrollStep = 25;
	Marquee1.Start();
});