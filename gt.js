'use strict';

var keywords = [];

const args = process.argv.slice(2)
var i=0;

var length = args.length;
if(length>5){length=5}

for(i=0; i <length; i++){
	//console.log(args[i])
	keywords[i]=args[i];
}


var googleTrends = require('google-trends-api');

/* ******************* Autocomplete **************************/

 // googleTrends.autoComplete({keyword: 'Back to school'})
 // .then((res) => {
 //   console.log('this is res', res);
 // })
 // .catch((err) => {
 //   console.log('got the error', err);
 //   console.log('error message', err.message);
 //   console.log('request body',  err.requestBody);
 // });

/* ******************* Interest over time **************************/

// googleTrends.interestOverTime({keyword: 'Valentines Day'})
// .then((res) => {
//   console.log('this is res', res);
// })
// .catch((err) => {
//   console.log('got the error', err);
//   console.log('error message', err.message);
//   console.log('request body',  err.requestBody);
// });

// googleTrends.interestOverTime({
//   keyword: 'Valentines Day',
//   startTime: new Date(Date.now() - (4 * 60 * 60 * 1000)),
//   granularTimeResolution: true,
// }, function(err, results) {
//   if (err) console.log('oh no error!', err);
//   else console.log(results);
// });

/* ****** Interest over time - Set a custom timezone ***************/

// googleTrends.interestOverTime({
//   keyword: 'Valentines Day',
//   timezone: new Date().getTimezoneOffset() / 60,
// }, function(err, results) {
//   if (err) console.log('oh no error!', err);
//   else console.log(results);
// });

/* ****** Interest over time - Comparing multiple keywords *********/
// googleTrends.interestOverTime({keyword: ['Valentines Day', 'Christmas Day']})
// .then((res) => {
//   console.log('this is res', res);
// })
// .catch((err) => {
//   console.log('got the error', err);
// })

/* ******************* Interest by region **************************/

googleTrends.interestByRegion({

  keyword: keywords,
  startTime: new Date('2018-11-01'),
  endTime: new Date('2018-11-30'),
  resolution: 'CITY',
  //geo: 'CD',
})
.then((res) => {
	var obj = JSON.parse(res);	

	var gmd = obj.default.geoMapData;
	//console.log(gmd.length)
	var i=0;
	var response='';
	for(i=0; i< gmd.length; i++){
		if(gmd[i].hasData[0]==true){
			response = response + gmd[i].geoName + '*' + gmd[i].value + '*' + gmd[i].coordinates.lat + '*' + gmd[i].coordinates.lng + '**';
		}
	}
	console.log(response);
})
.catch((err) => {
  console.log(err);
})

// googleTrends.interestByRegion({
//   keyword: 'Donald Trump',
//   startTime: new Date('2017-02-01'),
//   endTime: new Date('2017-02-06'),
//   geo: 'US-CA',
// })
// .then((res) => {
//   console.log(res);
// })
// .catch((err) => {
//   console.log(err);
// })

/* ******************* Related queries **************************/

// googleTrends.relatedQueries({keyword: 'Westminster Dog Show'})
// .then((res) => {
//   console.log(res);
// })
// .catch((err) => {
//   console.log(err);
// })

/* ******************* Related topics **************************/

// googleTrends.relatedTopics({
//   keyword: 'Chipotle',
//   startTime: new Date('2015-01-01'),
//   endTime: new Date('2017-02-10'),
// })
// .then((res) => {
//   console.log(res);
// })
// .catch((err) => {
//   console.log(err);
// });