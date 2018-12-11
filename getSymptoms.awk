BEGIN { 
	FS="	"
	diseaseSymptom = ""
}
#/Cholera/{print $0}
{
	if(toupper($2) == toupper(disease) && $4 >= 8  ){
		diseaseSymptom = diseaseSymptom  $4 "*" $1 "**"
	}
}

END{
	print diseaseSymptom
}