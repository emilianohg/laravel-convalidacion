SELECT
    alumnos.usuario_id,
    alumnos.numero_control,
    usuarios.nombre,
    usuarios.email,
    carreras.carrera_id,
    carreras.nombre as carrera,
    alumnos.plan_estudio_id as clave_plan_estudio,
    alumnos.semestre
FROM alumnos
INNER JOIN usuarios on alumnos.usuario_id = usuarios.id
INNER JOIN planes_estudio on alumnos.plan_estudio_id = planes_estudio.clave
INNER JOIN carreras on planes_estudio.carrera_id = carreras.carrera_id