import {useState} from 'react';
import axios from 'axios';

// import '../css/loading.css';




const host = ""
// const host = "http://localhost/arabitoapp"

const db = {
  getSucursales: data=>axios.get(host+"/getSucursales",{params:data}),
  getFallas: data=>axios.get(host+"/getFallas",{params:data}),

  
  

}

export default db