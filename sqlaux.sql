# decretos faltantes
SELECT 
    act_id, reub_id, reub_creacion, reub_creacion
FROM
    reubicaciones_activos
WHERE
    reub_id NOT IN (SELECT 
            reub_id
        FROM
            reubicaciones_activos
        GROUP BY act_id
        HAVING COUNT(reub_creacion) = 1)
        AND reub_id NOT IN (SELECT 
            reub_id
        FROM
            activos_fijos AF
                JOIN
            reubicaciones_activos REU ON AF.act_id = REU.act_id
        WHERE
            act_decretoAlta = 1
                AND (REU.act_id , REU.reub_creacion) IN (SELECT 
                    AF.act_id, MIN(REU.reub_creacion) AS fecha_min
                FROM
                    activos_fijos AF
                        JOIN
                    reubicaciones_activos REU ON AF.act_id = REU.act_id
                WHERE
                    act_decretoAlta = 1
                        AND REU.reub_decretado = 0
                GROUP BY REU.act_id
                HAVING COUNT(REU.act_id) > 1))
				
				
				


	
		#todos incluye el primero
        	select * from  activos_fijos AF 
                JOIN reubicaciones_activos REU
                	on AF.act_id = REU.act_id
                		where REU.reub_decretado = 0
							 	AND AF.act_id = ANY(SELECT AF.act_id 
             FROM activos_fijos AF 
                JOIN reubicaciones_activos REU
                	on AF.act_id = REU.act_id
         
                      WHERE act_decretoAlta = 1 
                       AND REU.reub_decretado = 0
							  		gROUP BY REU.act_id having  count(REU.act_id) > 1   ORDER BY act_codigo, act_descripcion          
														 )    
														 
														 
				select @currentRow := @currentRow + 1 AS rowNumber,  AF.act_id, act_codigo, act_codEstado, act_descripcion, anac_descripcion, aneg_codigo, aneg_descripcion,
             ccos_descripcion, ubi_descripcion, resp_nombre, act_fechaIngreso, act_codBarras, act_tipoControl, act_revalorizable, act_depreciable, 
             act_tipoDepreciacion, act_unidadMedidaVidaUtil, act_porLote, act_cantidadLote, act_situacionDePartida, act_fechaAdquisicion, 
             act_vidaUtilTributaria, act_valorAdquisicion, act_inicioRevalorizacion, reub_fecha AS ultFecha, reub_hora AS ultHora
			   from  activos_fijos AF 
                JOIN reubicaciones_activos REU
                	on AF.act_id = REU.act_id
                	JOIN areas_negocios AN
                            ON AF.aneg_id=AN.aneg_id
                                JOIN centros_costo CC
                                    ON AF.ccos_id=CC.ccos_id
                                        JOIN ubicaciones UB
                                            ON AF.ubi_id=UB.ubi_id
                                                JOIN responsables RE
                                                    ON UB.resp_id=RE.resp_id
                                                        LEFT JOIN analiticos_cuentas AC
                                                            ON AF.anac_id=AC.anac_id
                                                            	JOIN (SELECT @currentRow := 0) row
																where REU.reub_decretado = 0
																	AND AF.act_id = ANY(SELECT AF.act_id 
																		FROM activos_fijos AF 
																			JOIN reubicaciones_activos REU
																				on AF.act_id = REU.act_id
																					WHERE act_decretoAlta = 1 
																						AND REU.reub_decretado = 0
																							GROUP BY REU.act_id having  count(REU.act_id) > 1)    
																								ORDER BY act_codigo, act_descripcion 





																								
																								
																								
																								

#primeros
SELECT 
    AF.act_id, act_codigo, reub_id, reub_creacion
FROM
    activos_fijos AF
        JOIN
    reubicaciones_activos REU ON AF.act_id = REU.act_id
WHERE
    act_decretoAlta = 1
        AND (REU.act_id , REU.reub_creacion) IN (SELECT 
            AF.act_id, MIN(REU.reub_creacion) AS fecha_min
        FROM
            activos_fijos AF
                JOIN
            reubicaciones_activos REU ON AF.act_id = REU.act_id
        WHERE
            act_decretoAlta = 1
                AND REU.reub_decretado = 0
        GROUP BY REU.act_id
        HAVING COUNT(REU.act_id) > 1)







select act_id from reubicaciones_activos where act_id NOT in ()













$ solo los menores??
SELECT 

    AF.act_id,
    act_codigo,
    reub_id,
    reub_creacion

FROM
    activos_fijos AF
        JOIN
    reubicaciones_activos REU ON AF.act_id = REU.act_id
        JOIN
    areas_negocios AN ON AF.aneg_id = AN.aneg_id
        JOIN
    centros_costo CC ON AF.ccos_id = CC.ccos_id
        JOIN
    ubicaciones UB ON AF.ubi_id = UB.ubi_id
        JOIN
    responsables RE ON UB.resp_id = RE.resp_id
        LEFT JOIN
    analiticos_cuentas AC ON AF.anac_id = AC.anac_id
WHERE
    act_decretoAlta = 1
	AND (REU.act_id,REU.reub_creacion) IN(SELECT 
            AF.act_id, min(REU.reub_creacion) AS fecha_min
        FROM
            activos_fijos AF
                JOIN
            reubicaciones_activos REU ON AF.act_id = REU.act_id
        WHERE
            act_decretoAlta = 1
                AND REU.reub_decretado = 0
        GROUP BY REU.act_id
        HAVING COUNT(REU.act_id) > 1)









#original traslado


    $query = "SELECT COUNT(REU.act_id) lugares, AF.act_id, act_codigo, act_codEstado, act_descripcion, anac_descripcion, aneg_codigo, aneg_descripcion,
             ccos_descripcion, ubi_descripcion, resp_nombre, act_fechaIngreso, act_codBarras, act_tipoControl, act_revalorizable, act_depreciable, 
             act_tipoDepreciacion, act_unidadMedidaVidaUtil, act_porLote, act_cantidadLote, act_situacionDePartida, act_fechaAdquisicion, 
             act_vidaUtilTributaria, act_valorAdquisicion, act_inicioRevalorizacion, MAX(reub_fecha) ultFecha, MAX(reub_hora) ultHora
             FROM activos_fijos AF 
                JOIN reubicaciones_activos REU
                    ON AF.act_id=REU.act_id
                        JOIN areas_negocios AN
                            ON AF.aneg_id=AN.aneg_id
                                JOIN centros_costo CC
                                    ON AF.ccos_id=CC.ccos_id
                                        JOIN ubicaciones UB
                                            ON AF.ubi_id=UB.ubi_id
                                                JOIN responsables RE
                                                    ON UB.resp_id=RE.resp_id
                                                        LEFT JOIN analiticos_cuentas AC
                                                            ON AF.anac_id=AC.anac_id
                                                                WHERE act_decretoAlta = 1 
                                                                    GROUP BY REU.act_id HAVING lugares > 1 ORDER BY act_codigo, act_descripcion";



$temp traslado
SELECT 
    AF.act_id,
    act_codigo,
    act_codEstado,
    act_descripcion,
    anac_descripcion,
    aneg_codigo,
    aneg_descripcion,
    ccos_descripcion,
    ubi_descripcion,
    resp_nombre,
    act_fechaIngreso,
    act_codBarras,
    act_tipoControl,
    act_revalorizable,
    act_depreciable,
    act_tipoDepreciacion,
    act_unidadMedidaVidaUtil,
    act_porLote,
    act_cantidadLote,
    act_situacionDePartida,
    act_fechaAdquisicion,
    act_vidaUtilTributaria,
    act_valorAdquisicion,
    act_inicioRevalorizacion,
    reub_fecha AS ultFecha,
    reub_hora AS ultHora
FROM
    activos_fijos AF
        JOIN
    reubicaciones_activos REU ON AF.act_id = REU.act_id
        JOIN
    areas_negocios AN ON AF.aneg_id = AN.aneg_id
        JOIN
    centros_costo CC ON AF.ccos_id = CC.ccos_id
        JOIN
    ubicaciones UB ON AF.ubi_id = UB.ubi_id
        JOIN
    responsables RE ON UB.resp_id = RE.resp_id
        LEFT JOIN
    analiticos_cuentas AC ON AF.anac_id = AC.anac_id
WHERE
    reub_id NOT IN (SELECT 
            reub_id
        FROM
            reubicaciones_activos
        GROUP BY act_id
        HAVING COUNT(reub_id) = 1)
        AND reub_id NOT IN (SELECT 
            reub_id
        FROM
            activos_fijos AF
                JOIN
            reubicaciones_activos REU ON AF.act_id = REU.act_id
        WHERE
            act_decretoAlta = 1
                AND (REU.act_id , REU.reub_creacion) IN (SELECT 
                    AF.act_id, MIN(reub_fecha  | " " | reub_hora) AS fecha_min
                FROM
                    activos_fijos AF
                        JOIN
                    reubicaciones_activos REU ON AF.act_id = REU.act_id
                WHERE
                    act_decretoAlta = 1
                        AND REU.reub_decretado = 0
                GROUP BY REU.act_id
                HAVING COUNT(REU.act_id) > 1))
				
	












SELECT 
            reub_id,act_id
        FROM
            reubicaciones_activos
        
        where COUNT(act_id) != 1;
        
        
        
        
        
        SELECT 
                    MIN(REU.reub_id) As idReuInicial 
                FROM
                    activos_fijos AF
                        JOIN
                    reubicaciones_activos REU ON AF.act_id = REU.act_id
                WHERE
                    act_decretoAlta = 1
                        AND REU.reub_decretado = 0
                GROUP BY REU.act_id
                HAVING COUNT(REU.act_id) > 1
                
                
                
                
                



