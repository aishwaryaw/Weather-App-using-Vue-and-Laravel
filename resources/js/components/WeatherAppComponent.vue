<template>
<div class="mb-10">

<div class="places-input max-w-lg rounded-lg mx-10 my-2">
    <input type="search" id="city" class="form-control" placeholder="In which city do you live?" />
        <p>Selected: <strong id="address-value">none</strong></p>

</div>
<div class="max-w-lg mx-10 border-rounded rounded-lg overflow-hidden mt-2 text-white">
    <div class="flex items-center justify-between bg-gray-900 pb-4">
        <div v-if="load" class="flex flex-col mx-4">
            <div class="text-6xl">{{ currentTemperature.actual }}°C</div>
            <div class="pb-2">
               Feels like {{ currentTemperature.feels }}°C
            </div>
        </div>

        <div v-if="load" class="">
            <div>
                <h3>{{ currentTemperature.summary }}</h3>
            </div>
            <div>
                <p>{{location.name}}</p>
            </div>
        </div>

        <div class="mx-4">
            <div>
                <div v-if="!load"><h2>loading..</h2></div>
                <canvas id="icon" width="75" height="75"></canvas>
               
            </div>
           
        </div>

    </div>


<div v-for="(temp,index) in dailyFiveDays"
            :key="temp.time">
     <div class="flex items-center justify-between bg-gray-800 text-white overflow-hidden px-6 py-8 border-b-2 border-gray-900">
        <div class="mx-4 w-1/6">
            <div class="text-xl">{{toDayOfWeek(temp.time) }}</div>
          
        </div>

        <div class="w-4/6 flex items-center">
            <div class="mx-4">
                 <canvas :id="`icon${index +1 }`" width="30" height="30" :data-icon="toKebabCase(temp.icon)"></canvas>
               
            </div>
            <div>
                <p>{{ temp.summary }}</p>
            </div>
        </div>

        <div class="w-1/6 mx-4 text-center">
            <div>
                <h5>{{ temp.temperatureHigh }}°C</h5>
            </div>

              <div>
                <h5>{{ temp.temperatureLow }}°C </h5>
            </div>
           
        </div>
</div>
   

    </div>
</div>
</div>
  
</template>


<script>

export default {

mounted(){

this.fetchdata();

  var placesAutocomplete = places({
    appId: '',
    apiKey: '',
    container: document.querySelector('#city'),
  
  }).configure({
    type: 'city',
    aroundLatLngViaIP: false,
  });
  
  var $address = document.querySelector('#address-value')

  placesAutocomplete.on('change', (e) => {
    $address.textContent = e.suggestion.value
    
    this.location.name = `${e.suggestion.name}, ${e.suggestion.country}`
    this.location.lat = e.suggestion.latlng.lat
    this.location.lng = e.suggestion.latlng.lng
  });

  placesAutocomplete.on('clear', function() {
    $address.textContent = 'none';
    
  });


},  
watch: {
    location: {
      handler(newValue, oldValue) {
        this.fetchdata()
      },
      deep: true
    }
  },

computed: {
    dailyFiveDays() {
      return this.dailyTemperatures.filter((temp, index) => index < 5 )
    }
  },


data(){

    return{

        load:false,

        currentTemperature :{

            actual:'',
            feels:'',
            summary:'',
            icon:''

        },

        location: {
        name: 'Toronto, Canada',
        lat: 43.6532,
        lng: -79.38323,
      },

        dailyTemperatures : []
        }
    },

methods:
{
    fetchdata(){

    var skycons = new Skycons({"color": "white"});
    axios.get(`/api/weather?lat=${this.location.lat}&lng=${this.location.lng}`).
    then(
    (response)=>
    {   
        this.load = true;
        console.log(response);
        this.currentTemperature.actual =Math.round( response.data.currently.temperature);
        this.currentTemperature.feels = Math.round(response.data.currently.apparentTemperature);
        this.currentTemperature.summary = response.data.currently.summary;
        this.currentTemperature.icon = this.toKebabCase(response.data.currently.icon);

        this.dailyTemperatures = response.data.daily.data;
       
  
    // you can add a canvas by it's ID...
    skycons.add("icon", this.currentTemperature.icon );
     skycons.play();

    this.$nextTick(()=>
    {
    skycons.add("icon1", document.getElementById('icon1').getAttribute('data-icon') );
    skycons.add("icon2", document.getElementById('icon2').getAttribute('data-icon') );
    skycons.add("icon3", document.getElementById('icon3').getAttribute('data-icon') );
    skycons.add("icon4", document.getElementById('icon4').getAttribute('data-icon') );
    skycons.add("icon5", document.getElementById('icon5').getAttribute('data-icon') );
    skycons.play();

    }

 

    );
 
   
    

   

    }
);
        
},


    toKebabCase(string){

        return string.split(' ').join('-');
    },

    toDayOfWeek(timestamp) {
      const newDate = new Date(timestamp*1000)
      const days = ['SUN','MON','TUE','WED','THU','FRI','SAT']
      return days[newDate.getDay()]
    }
  
}


}

</script>
