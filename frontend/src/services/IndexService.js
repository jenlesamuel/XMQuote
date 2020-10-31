import axios from 'axios'

let apiClient = axios.create({
  baseURL: `http://localhost:8001/api`, 
  withCredentials: false, // This is the default
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  }
})

function getListings() {
   return apiClient.get("/listings");
}

function getQuotes(data) {
  let {symbol, startDate, endDate, email} = data;
  let path = `/quotes?symbol=${symbol}&startDate=${startDate}&endDate=${endDate}&email=${email}`
  return apiClient.get(path);
}

export default {
    getListings,
    getQuotes
}