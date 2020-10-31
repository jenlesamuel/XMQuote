<template>
  <div>
    <h3>Home</h3>

    <p v-if="errors.length" class="errors">
    <b>Please correct the following error(s):</b>
    <ul>
      <li v-for="(error,idx) in errors" :key="idx">{{ error }}</li>
    </ul>
    </p>

    <form id="idxForm" v-on:submit.prevent="getQuote">
      <div class="field">
        <label for="symbol">Symbol</label>
        <select v-model="quoteform.symbol" required>
          <option value="">Select Company Symbol</option>
          <option v-for="(company, idx) in companies" :key="idx" :value="company.Symbol">{{company['Symbol']}}</option>
        </select>
      </div>
      <div class="field">
        <label for="startDate">Start Date</label>
        <datepicker placeholder="Select Start Date" name="startDate" :required="true" v-model="quoteform.startDate" :disabled-dates="disabledDates"></datepicker>
      </div>
      <div class="field">
        <label for="endDate">End Date</label>
        <datepicker placeholder="Select End Date" format="yyyy-MM-dd" name="endDate" :required="true" v-model="quoteform.endDate" :disabled-dates="disabledDates"></datepicker>
      </div>
      <div class="field">
        <label for="email">Email</label>
        <input type="text" name="email" v-model="quoteform.email" required/>
      </div>
      <input type="submit" value="Get Quotes">
    </form>
    <div id="xm-charts">
      <div class="quote-table">
        <table style="width:100%">
          <tr>
            <th>Date</th>
            <th>Open</th>
            <th>High</th>
            <th>Low</th>
            <th>Close</th>
            <th>Volume</th>
          </tr>
          <tr v-for="(price, idx) in prices" :key="idx" >
            <td>{{price.date}}</td>
            <td>{{price.open}}</td>
            <td>{{price.high}}</td>
            <td>{{price.low}}</td>
            <td>{{price.close}}</td>
            <td>{{price.volume}}</td>
          </tr>
        </table>
      </div>
      <div class="quote-chart">
          <apexchart type="candlestick" height="350" :options="chartOptions" :series="series"></apexchart>
      </div>
    </div>
  </div>
</template>

<script>
import IndexService from "@/services/IndexService";
import Datepicker from 'vuejs-datepicker';

let quoteform = {
  symbol: "",
  startDate: "",
  endDate: "",
  email: ""
}
export default {
  name: "IndexForm",
  components: {
    Datepicker
  },
  data() {
    return {
        quoteform: quoteform,
        companies: [],
        disabledDates: {
          from: new Date()
        },
        errors:[],
        prices: [],
        chartOptions: {
            chart: {
              type: 'candlestick',
              height: 350
            },
            title: {
              text: 'CandleStick Chart',
              align: 'left'
            },
            xaxis: {
              type: 'datetime'
            },
            yaxis: {
              tooltip: {
                enabled: true
              }
            }
          },
        series:[{
          data: []
        }]
    }
  },
  methods: {
    getQuote() {
        this.validateForm();
        if (this.errors.length !== 0) {
          return;
        }

        let formData = {...this.quoteform};
      
        formData.startDate = this.formatDate(formData.startDate);
        formData.endDate = this.formatDate(formData.endDate);
        IndexService.getQuotes(formData)
        .then((response) => {
          let data = response.data;
        
          if (data.prices !== undefined) {
            this.prices = data.prices;
            this.pricesToChartData(this.prices);
          } else {
            console.log("undefined");
          }
        }).catch((error) => {
          console.log('There was an error:', error);
        })
    },
    loadCompanies() {
      IndexService.getListings()
      .then((response) => {
          this.companies = response.data;
      }).catch((error) => {
        console.log('There was an error:', error.response);
      })
    },
    pricesToChartData(prices) {
       let data = prices.map((price) => {
         return {
           x: new Date(price.date),
           y: [price.open, price.high, price.low, price.close]
         }
       })

       let series = [];
       series.push({data})
       this.series = series;
    },
    formatDate(dateObj) {
      let month = '' + (dateObj.getMonth() + 1);
      let day = '' + dateObj.getDate();
      let year = dateObj.getFullYear();

      if (month.length < 2) 
          month = '0' + month;
      if (day.length < 2) 
          day = '0' + day;

      return [year, month, day].join('-');
    },
    validateForm() {
        this.errors = [];

        if (this.quoteform.symbol === "") {
          this.errors.push("Please select a symbol");
        }
        
        if (! this.isValidEmail(this.quoteform.email)) {
          this.errors.push("Please enter a valid email");
        }

        this.validateDates(this.quoteform.startDate, this.quoteform.endDate);
    },
    isValidEmail(email) {
      let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    },
    validateDates(startDate, endDate) {
      let currentDate = new Date();
      if (startDate === "" || startDate > currentDate || startDate > endDate) {
        this.errors.push("Enter a valid Start Date not greater than Current Date or End Date")
      }
      if (endDate === "" || endDate > currentDate) {
        this.errors.push("End date cannot be greater than Current Date");
      }
    }
  },
  created() {
    this.loadCompanies();
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  label {
    display: block;
  }

  #idxForm .vdp-datepicker {
    text-align: center;
  }

  #xm-charts {
    display: flex;
  }

  .errors {
    color: orangered;
  }
</style>
