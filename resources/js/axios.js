import axios from 'axios'
const domain = '';
const token = localStorage.getItem('token') ? localStorage.getItem('token') : '';
export default axios.create({
    domain,
    headers: {
"Authorization" : "Bearer "+ token
    }
})