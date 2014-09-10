function formhashregister(form, password)
{
	//password
	var p = document.createElement("input");
	var p_length = document.createElement("input");
	
	//password
	form.appendChild(p);
	p.name = "p";
	p.type = "hidden";
	
	
	form.appendChild(p_length);
	p_length.name="length";
	p_length.type="hidden";
	
	//password
	if(form.elements["password"].value.length < 8)
	{
		p_length.value = "short";
	}
	else
	{
		p_length.value = "fine";
	}


	p.value = hex_sha512(password.value);
	password.value = "";

	form.submit();
}

function formhashlogin(form, password)
{
	var p = document.createElement("input");
	var passexist = document.createElement("input");
	form.appendChild(p);
	p.name = "p";
	p.type = "hidden";
	
	form.appendChild(passexist);
	passexist.name="passexist";
	passexist.type="hidden";
	if(form.elements["password"].value.length == 0)
	{
		passexist.value = "false";
	}
	else
	{
		passexist.value = "true";
	}
	
	p.value = hex_sha512(password.value);
	password.value = "";
	
	form.submit();
}