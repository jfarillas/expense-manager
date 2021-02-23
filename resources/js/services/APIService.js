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
    const flag = 'update';
    const url = `${API_URL}/${route}/${id}/${flag}`;
    //const url = `${API_URL}/${route}/${id}`;
    //let payload = JSON.stringify(data);
    console.log(payload);
    return await axios.post(url, payload, {withCredentials: true});
    //return await axios.patch(url, payload, {withCredentials: true});
  }

  async updatePassword(route, payload, id) {
    const flag = 'update-password';
    const url = `${API_URL}/${route}/${id}/${flag}`;
    //let payload = JSON.stringify(data);
    console.log(payload);
    return await axios.post(url, payload, {withCredentials: true});
    //return await axios.put(url, payload, {withCredentials: true});
  }

  async deleteData(route, id) {
    const flag = 'delete';
    const url = `${API_URL}/${route}/${id}/${flag}`;
    //const url = `${API_URL}/${route}/${id}`;
    let payload = {
      flag: 1
    }; 
    return await axios.post(url, payload, {withCredentials: true});
    //return await axios.delete(url, {withCredentials: true});
  }
}
