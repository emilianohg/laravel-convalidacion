SELECT
    carreras.carrera_id,
    asignaturas.asignatura_id,
    asignaturas.plan_estudio_clave,
    carreras.nombre as carrera,
    asignaturas.nombre,
    asignaturas.creditos,
    planes_estudio.es_vigente
FROM asignaturas
INNER JOIN planes_estudio on asignaturas.plan_estudio_clave = planes_estudio.clave
INNER JOIN carreras ON planes_estudio.carrera_id = carreras.carrera_id