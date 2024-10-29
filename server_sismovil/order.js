import React, { useState, useRef, useEffect } from 'react';
import {Modal, ModalBody, ModalFooter, ModalHeader} from 'reactstrap';
import { OverlayTrigger, Popover, Button } from 'react-bootstrap';
import '../assets/css/services.css';
import axios from 'axios';

export const Services = () => {
    const baseUrl="http://localhost/server_sismovil/apiServicios/"
    const [data, setData]=useState([]);
    const [modalCrear, setModalCrear]= useState(false);
    const [modalEditar, setModalEditar]= useState(false);
    const [modalEliminar, setModalEliminar]= useState(false);

    const [serviciosSeleccionado, setServicioSeleccionado]=useState({
        id: '',
        nombre: ''
    });

    const handleChange=e=>{
        const {name, value}=e.target;
        setServicioSeleccionado((prevState)=>({
            ...prevState,
            [name]: value
        }))
        console.log(serviciosSeleccionado);
    }

    const abrirCerrarModalCrear=()=>{
        setModalCrear(!modalCrear);
    }

    const abrirCerrarModalEditar=()=>{
        setModalEditar(!modalEditar);
    }

    const abrirCerrarModalEliminar=()=>{
        setModalEliminar(!modalEliminar);
    }

    const peticionGet=async()=>{
        await axios.get(baseUrl)
        .then(response=>{
            console.log(response.data);
        }).catch(error=>{
            console.log(error);
        })
    }

    const peticionPost=async()=>{
        var f = new FormData();
        f.append("nombre", serviciosSeleccionado.nombre);
        f.append("METHOD", "POST");
        await axios.post(baseUrl, f)
        .then(response=>{
            setData(data.concat(response.data));
            abrirCerrarModalCrear();
            peticionGet();
        }).catch(error=>{
            console.log(error);
        })
    }

    const peticionPut=async()=>{
        var f = new FormData();
        f.append("nombre", serviciosSeleccionado.nombre);

        f.append("METHOD", "PUT");
        await axios.post(baseUrl, f, {params: {id: serviciosSeleccionado.id}})
        .then(response=>{
            var dataNueva= data;
            dataNueva.map(servicio=>{
                if(servicio.id===serviciosSeleccionado.id){
                    servicio.nombre=serviciosSeleccionado.nombre;

                }
            });
            setData(dataNueva)
            abrirCerrarModalEditar();
        }).catch(error=>{
            console.log(error);
        })
    }

    const peticionDelete=async()=>{
        var f = new FormData();
        f.append("METHOD", "DELETE");
        await axios.post(baseUrl, f, {params: {id: serviciosSeleccionado.id}})
        .then(response=>{
            setData(data.filter(servicio=>servicio.id!==serviciosSeleccionado.id));
            abrirCerrarModalEliminar();
        }).catch(error=>{
            console.log(error);
        })
    }

    const seleccionarServicio=(servicios, caso)=>{
        setServicioSeleccionado(servicios);

        (caso==="Editar")?
        abrirCerrarModalEditar():
        abrirCerrarModalEliminar()
    }

    useEffect(()=>{
        peticionGet();
    },[])


    const [filterValue, setFilterValue] = useState('');
    const [showPopover, setShowPopover] = useState([]);

    useEffect(() => {
        setShowPopover(new Array(servicios.length).fill(false));
    }, []);

    const togglePopover = (index) => {
        const newShowPopover = [...showPopover];
        newShowPopover[index] = !newShowPopover[index];
        setShowPopover(newShowPopover);
    };

    const handleFilterChange = (event) => {
        setFilterValue(event.target.value);
    };

    // Declare buttonRef1 using useRef
    const buttonRef1 = useRef(null);

    // Datos de ejemplo para la tabla
    const servicios = [
        { id: 1, nombre: 'Microsoldadura' },
        { id: 2, nombre: 'Pin de Carga' },
        { id: 3, nombre: 'Instalación de Pantalla' },
        { id: 4, nombre: 'Cambio de Teclados' },
        { id: 5, nombre: 'Limpieza / Varios' },
        { id: 6, nombre: 'Mantenimiento del Equipo' },
        { id: 7, nombre: 'Equipo Mojado / Varios' },
        { id: 8, nombre: 'Revisión de Equipo' },
        { id: 9, nombre: 'Instalación de Batería' },
        { id: 10, nombre: 'Otro' },
    ];

    return (
        <div className="services">
          <div className="services-header">
            <div className="services-buttons">
              <button className="button-left">
                <i className="fa-solid fa-arrow-left"></i>
              </button>
              <span className="services-title">Servicios</span>
            </div>
            <div className="create-button-container">
              <button className="create-button">Crear Servicio</button>
            </div>
          </div>
          <div
          className="show-services"
                style={{
                  backgroundColor: '#f0f0f0', // Color de fondo gris suave
                  borderWidth: '1px', // Grosor del borde
                  borderRadius: '.25rem', // Radio de borde
                  boxShadow: '0 0 10px rgba(0, 0, 0, 0.1)', // Sombra
                  padding: '1rem', // Espaciado interno
                  marginTop: '2rem', // Margen superior
                  width: '100%', // Ancho máximo menos el doble del grosor del borde (para evitar desbordamiento)
                  boxSizing: 'border-box', // Incluir padding y borde en el cálculo del ancho
                }}
            >
                <input
                    type="text"
                    placeholder="Buscar por nombre del servicio o descripción"
                    value={filterValue}
                    onChange={handleFilterChange}
                    className="form-control mb-3"
                    style={{ width: '50%' }}
                />

                <table className="table items-center w-full border-collapse">
                    <thead>
                        <tr className="px-2 border-b-2">
                            <th className="py-3 font-bold text-left border-l-0 border-r-0 whitespace-nowrap"></th>
                            <th className="py-3 font-bold text-left border-l-0 border-r-0 whitespace-nowrap">Servicio</th>
                        </tr>
                    </thead>
                    <tbody>
                        {servicios.map((servicio, index) => (
                            <tr
                                key={servicio.id}
                                className="service-row"
                            >
                                <td className="p-2 border-t" style={{ minWidth: '50px', width: '50px' }}>
                                    <OverlayTrigger
                                        trigger="click"
                                        placement="bottom"
                                        show={showPopover[index]}
                                        overlay={
                                            <Popover>
                                                <Popover.Body>
                                                    <Button variant="secondary">Editar</Button>{' '}
                                                    <Button variant="danger">Eliminar</Button>
                                                </Popover.Body>
                                            </Popover>
                                        }
                                    >
                                        <Button
                                            ref={buttonRef1}
                                            onClick={() => togglePopover(index)}
                                            variant="primary">
                                                <i className="fa-solid fa-chevron-right"></i>
                                        </Button>
                                    </OverlayTrigger>
                                </td>
                                <td className="p-2 border-t" style={{ minWidth: '150px' }}>
                                    {servicio.nombre}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
};
