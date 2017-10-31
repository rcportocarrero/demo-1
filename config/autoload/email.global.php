<?php

return array(
    // Datos de correo
    'datos_correo' => array(
        'bcc' => 'evaluaciondocente@perueduca.pe',
        'from' => 'evaluaciondocente@perueduca.pe',
        'subject' => 'Selección de IIEE - Concursos Públicos de Ingreso a la Carrera Pública Magisterial y de Contratación Docente en Instituciones Públicas de Educación Básica 2015.',
        'html' => '<p style=\"font-size:11px;font-family:verdana\">Estimado(a) profesor(a): {{nombre_apellidos}} <br><br>'
        . 'Recuerde verificar que los datos consignados en el formato digital de selección de plazas de postulación sean correctos.<br><br>'
        . 'Atentamente,<br><br>'
        . 'Direcci&oacute;n de Evaluaci&oacute;n Docente</p>',
    ),
    'datos_correo_registro' => array(
        'bcc' => 'evaluaciondocente@perueduca.pe',
        'from' => 'evaluaciondocente@perueduca.pe',
        'subject' => 'Registro de Trayectoria - Concursos Públicos de Ingreso a la Carrera Pública Magisterial y de Contratación Docente en Instituciones Públicas de Educación Básica 2015.',
        'html' => '<p style=\"font-size:11px;font-family:verdana\">Estimado(a) profesor(a): {{nombre_apellidos}} <br><br>'
        . 'Recuerde verificar la información que se consigna en el formulario de Declaración de Requisitos y Registro de Trayectoria Profesional.<br><br>'
        . 'Atentamente,<br><br>'
        . 'Direcci&oacute;n de Evaluaci&oacute;n Docente</p>',
    ),
    'datos_correo_registro_dj' => array(
        'bcc' => 'evaluaciondocente@perueduca.pe',
        'from' => 'evaluaciondocente@perueduca.pe',
        'subject' => 'Registro de Trayectoria - Concursos Públicos de Ingreso a la Carrera Pública Magisterial y de Contratación Docente en Instituciones Públicas de Educación Básica 2015.',
        'html' => '<p style=\"font-size:11px;font-family:verdana\">Estimado(a) profesor(a): {{nombre_apellidos}} <br><br>'
        . 'Recuerde verificar la información que se consigna en el formulario de Declaración de Requisitos y Registro de Trayectoria Profesional.<br><br>'
        . 'Atentamente,<br><br>'
        . 'Direcci&oacute;n de Evaluaci&oacute;n Docente</p>',
    ),
    'envio_correo_reclamos' => array(
        'name' => 'perueduca.pe',
        'host' => '192.168.210.xx',
        'port' => '25',
        //'connection_class' => 'plain',
        //'connection_config' => array(
        //    'username' => 'carlos.sing@gowebperu.com',
        //    'password' => '$ing&$had0w'
        //),
    )
);
