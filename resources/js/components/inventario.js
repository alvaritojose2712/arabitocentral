import { useHotkeys } from 'react-hotkeys-hook';


import {useState,useEffect, useRef,StrictMode} from 'react';
import ReactDOM, {render} from 'react-dom';
import db from '../database/database';
import Header from './header';
import SelectSucursal from './selectSucursal';

import FallasComponent from './fallas';
import Reportes from './reportes';



function Inventario() {
  const [view,setView] = useState("inventario")
  const [loading,setLoading] = useState(false)

  const [sucursales,setsucursales] = useState([])
  const [sucursalSelect,setsucursalSelect] = useState(null)

  const [fallas,setfallas] = useState([])
  const getSucursales = () => {
    setLoading(true)
    db.getSucursales().then(res=>{
      setsucursales(res.data)
      setLoading(false)
    })
  }

  const getFallas = () => {
    setLoading(true)

    if (sucursales.filter(e=>e.char==sucursalSelect).length) {
      db.getFallas({id_sucursal: sucursales.filter(e=>e.char==sucursalSelect)[0].id }).then(res=>{
        setfallas(res.data)
        setLoading(false)
      })

    }
  }



  useEffect(()=>{
    getSucursales()
  },[])

  useEffect(()=>{
    getFallas()
  },[sucursalSelect])

  useEffect(()=>{
    if (view=="fallas") {
      getFallas()

    }
  },[view])
  return(
    <>
      <Header 
      setView={setView} 
      view={view}
      sucursalSelect={sucursalSelect}
      setsucursalSelect={setsucursalSelect}
      />
      <div className="container">
        {sucursalSelect===null?
          <SelectSucursal 
          setsucursalSelect={setsucursalSelect} 
          sucursalSelect={sucursalSelect}
          sucursales={sucursales}
          />
        :<>
          {view=="fallas"?<FallasComponent
            fallas={fallas}
          />:null}
          
          
        </>}


      </div>
    </>
  );
}
render(<Inventario/>,document.getElementById('app'));

