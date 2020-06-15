import axios from 'axios';

const API_URL = `${process.env.MIX_API_URL}`;
 
export class APIService {

  constructor(){
  }

  async getData(route, id) {
    let url = (typeof id === 'undefined' || id === null) 
      ? `${API_URL}/${route}`
      : `${API_URL}/${route}/${id}`;
    return await axios.get(url, {withCredentials: true});
  }

  async addData(route, payload) {
    const url = `${API_URL}/${route}`;
    //let payload = JSON.stringify(data);
    console.log(payload);
    return await axios.post(url, payload, {withCredentials: true});
  }

  async updateData(route, payload, id) {
    const url = `${API_URL}/${route}/${id}`;
    //let payload = JSON.stringify(data);
    console.log(payload);
    return await axios.put(url, payload, {withCredentials: true});
  }

  async updatePassword(route, payload, id) {
    const url = `${API_URL}/${route}/${id}/update-password`;
    //let payload = JSON.stringify(data);
    console.log(payload);
    return await axios.put(url, payload, {withCredentials: true});
  }

  async deleteData(route, id) {
    const url = `${API_URL}/${route}/${id}`;
    return await axios.delete(url, {withCredentials: true});
  }
}
