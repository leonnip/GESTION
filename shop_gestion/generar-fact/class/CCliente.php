<?php
class CCliente
{
    private $_empresa;
    private $_cifnif;
    private $_direccion;
    private $_codigo_postal;
    private $_ciudad;
	private $_telefono;
	private $_fechaOrden;
   
    private $_sMensaje;
 
    public function __construct($empresa, $cifnif, $direccion, $codigo_postal, $ciudad, $telefono, $fechaOrden) 
    {
        $this->_empresa = $empresa;
        $this->_cifnif = $cifnif;
        $this->_direccion = $direccion;
        $this->_codigo_postal = $codigo_postal;
        $this->_ciudad = $ciudad;
		$this->_telefono = $telefono;
		$this->_fechaOrden = $fechaOrden;
    }
     
    //===========================     SETS     ===========================
    public function set_empresa($empresa)
    {
        $this->_empresa = $empresa;
    }
 
    public function set_cifnif($cifnif)
    {
        $this->_cifnif = $cifnif;
    }
     
    public function set_direccion($direccion)
    {
        $this->_direccion = $direccion;
    }
     
    public function set_codigo_postal($codigo_postal)
    {
        $this->_codigo_postal = $codigo_postal;
    }
     
    public function set_ciudad($ciudad)
    {
        $this->_ciudad = $ciudad;
    }    
	public function set_telefono($telefono)
	{
		$this->_telefono = $telefono;
	}
	public function set_fechaOrden($fechaOrden)
	{
		$this->_fechaOrden = $fechaOrden;
	}
 
    /*
    public function set_propiedad($propiedad)
    {
        $this->_propiedad = $propiedad;
    }*/
     
    //===========================     GETS     ===========================
    public function get_empresa()
    {
        return $this->_empresa;
    }
     
    public function get_cifnif()
    {
        return $this->_cifnif;
    }
    public function get_direccion()
    {
        return $this->_direccion;
    }
    public function get_codigo_postal()
    {
        return $this->_codigo_postal;
    }
    public function get_ciudad()
    {
        return $this->_ciudad;
    }
	public function get_telefono()
	{
		return $this->_telefono;
	}
    public function get_fechaOrden() {
		return $this->_fechaOrden;
	} 
	 
    /*
    public function get_propiedad()
    {
        return $this->_propiedad;
    }
    */
     
    //===========================     METODOS     ===========================    
    public function is_error()
    {
        if(!empty($this->_sMensaje))
        {
            return true;
        }
        return false;
    }
     
    public function get_mensaje()
    {
        return $this->_sMensaje;
    }
}
?>