import { useHotkeys } from 'react-hotkeys-hook';


import {useState,useEffect, useRef,StrictMode} from 'react';
import ReactDOM, {render} from 'react-dom';
import db from '../database/database';
import Header from './header';
import SelectSucursal from './selectSucursal';

import FallasComponent from './fallas';
import VentasComponent from './ventas';
import GastosComponent from './gastos';

import Toplabel from './toplabel';




function Inventario() {
  const [view,setView] = useState("inventario")
  const [loading,setLoading] = useState(false)

  const [sucursales,setsucursales] = useState([])
  const [sucursalSelect,setsucursalSelect] = useState(null)

  const [fallas,setfallas] = useState([])
  const [gastos,setgastos] = useState([])
  const [ventas,setventas] = useState([])
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

  const getGastos = () => {
    setLoading(true)

    if (sucursales.filter(e=>e.char==sucursalSelect).length) {
      db.getGastos({id_sucursal: sucursales.filter(e=>e.char==sucursalSelect)[0].id }).then(res=>{
        setgastos(res.data)
        setLoading(false)
      })

    }
  }

  const getVentas = () => {
    setLoading(true)

    if (sucursales.filter(e=>e.char==sucursalSelect).length) {
      db.getVentas({id_sucursal: sucursales.filter(e=>e.char==sucursalSelect)[0].id }).then(res=>{
        setventas(res.data)
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

    }else if (view=="gastos") {
      getGastos()
    }else if (view=="ventas") {
      getVentas()
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
      <Toplabel sucursales={sucursales} sucursalSelect={sucursalSelect}/>
      <div className="container marginb-6 margint-6">
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

          {view=="gastos"?<GastosComponent
            gastos={gastos}
          />:null}

          {view=="ventas"?<VentasComponent
            ventas={ventas}
          />:null}

          
          
          
        </>}
      </div>

    </>
  );
}
render(<Inventario/>,document.getElementById('app'));

