BEGIN { 
	FS="	"
	diseaseSymptom = ""
}
{
	if(toupper($1) == toupper(disease) && $3 >=0.7){
		similarDisease = $2;
		diseaseSymptom = diseaseSymptom  $3 "*" similarDisease "**"
	}else if( toupper($2) == toupper(disease)  && $3 >=0.7){
		similarDisease =$1
		diseaseSymptom = diseaseSymptom  $3 "*" similarDisease "**"
	}
	

}

END{
	print diseaseSymptom
}