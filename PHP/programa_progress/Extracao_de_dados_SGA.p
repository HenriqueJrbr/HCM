/*
Extrai dados Acessos TOTVS - ImplantSGA Sem Configura‡äes.
*/
DEFINE VARIABLE c_caminho AS CHARACTER NO-UNDO.
DEFINE VARIABLE c-saida   AS CHARACTER NO-UNDO.
DEFINE VARIABLE h-acomp   AS HANDLE NO-UNDO.

DEFINE VARIABLE iEmpresa        AS INTEGER  LABEL 'Informe a sequencia!' NO-UNDO.
DEFINE VARIABLE cDescInstancia  AS CHARACTER LABEL 'Informe o nome da instancia de extra‡Æo' NO-UNDO.
DEFINE VARIABLE cRoot           AS CHARACTER NO-UNDO.

&SCOPED-DEFINE PM_NOREMOVE 0

DEFINE VARIABLE Msg     AS MEMPTR  NO-UNDO.
DEFINE VARIABLE lResult AS INTEGER NO-UNDO.
SET-SIZE(Msg) = 48. /* big enough for 64-bit */


DEFINE STREAM A. 
DEFINE STREAM B. 
DEFINE STREAM C.



/*AMARRAÇÕES VIRTUAL*/
DEFINE TEMP-TABLE tt-usuario NO-UNDO 
       FIELD idUsuario AS int
       FIELD cod_usuario LIKE usuar_mestre.cod_usuario
       INDEX usr cod_usuario ASC.
     
       
DEFINE TEMP-TABLE tt-grupo NO-UNDO 
       FIELD idGrupo AS int
       FIELD cod_grupo LIKE grp_usuar.cod_grp_usuar
       FIELD idInstancia AS INTEGER 
       INDEX grp  cod_grupo idInstancia ASC.
       
DEFINE TEMP-TABLE tt-programa NO-UNDO 
        FIELD idPrograma AS int
        FIELD cod_programa LIKE prog_dtsul.cod_prog_dtsul
        INDEX prog  cod_programa ASC.
   
   
 DEFINE TEMP-TABLE tt-referencias no-undo
        FIELD iUltimaInstancia AS INTEGER 
        FIELD cUltimaDescInstancia AS CHARACTER
        FIELD iUltimoUsuario AS INTEGER 
        FIELD iUltimoGrupo AS INTEGER
        FIELD iUltimoGrpUsr AS INTEGER
        FIELD iUltimoUsrEmpresa AS INTEGER
        FIELD iUltimoProgEmpresa AS INTEGER 
        FIELD iUltimoPrograma AS INTEGER 
        FIELD iUltimoGrpProg AS INTEGER
        .
              
  
/*AMARRA€åS VIRTUAL*/
DEFINE VARIABLE lPrimeiraExec AS LOGICAL INITIAL NO NO-UNDO.

ASSIGN cRoot = SESSION:TEMP-DIRECTORY  + "export_sga".
OS-COMMAND SILENT  VALUE('MKDIR ' + cRoot).

IF search(cRoot + "\referencias.csv") <> ? THEN DO:
    INPUT FROM VALUE(cRoot + "\referencias.csv").
    REPEAT:
        CREATE tt-referencias.
        IMPORT DELIMITER ";" 
               tt-referencias.  
    END.
    INPUT CLOSE.   
END.
ELSE DO:
    ASSIGN lPrimeiraExec = YES.
    CREATE tt-referencias.
END.

FIND FIRST tt-referencias NO-ERROR.

FORM iEmpresa
     cDescInstancia
     WITH FRAME a.

MESSAGE "Ao executar este programa informe a sequˆncia num‚rica por instƒncia que est  " skip
        "executando. Exemplo:" SKIP 
        "Primeira instƒncia, informe 1, segunda instƒncia, informe 2 e assim sucessivamente... " skip
        "De uma descri‡Æo com uma palavra para que as extra‡äes sejam separadas por esta, em seguida pressione F2 para dar sequˆncia!"
VIEW-AS ALERT-BOX.

IF AVAILABLE tt-referencias THEN DO:

    ASSIGN iEmpresa:SCREEN-VALUE IN FRAME a = string(tt-referencias.iUltimaInstancia + 1).
END.


    
DO WITH FRAME a:    
    PROMPT-FOR iEmpresa  cDescInstancia .
END.

ASSIGN iEmpresa  cDescInstancia .

IF iEmpresa = 0 OR iEmpresa = ? THEN  DO:  
    MESSAGE 'Sequencia Inv lida'
    VIEW-AS ALERT-BOX.
    RETURN.
END.

IF cDescInstancia = "" THEN DO:
    MESSAGE 'Informe uma descri‡Æo!' 
    VIEW-AS ALERT-BOX.
    RETURN.
END.


ASSIGN c-saida = cRoot + "\" + cDescInstancia.
OS-COMMAND SILENT  VALUE('MKDIR ' + c-saida).


/*LOAD CARGAS ANTERIORES*/
DEFINE VARIABLE linha AS INTEGER NO-UNDO.
DEFINE VARIABLE iId  AS INTEGER NO-UNDO.
DEFINE VARIABLE cDesc AS CHARACTER NO-UNDO.


IF NOT lPrimeiraExec THEN DO:    
    IF SEARCH(cRoot + "\usuarios.csv") <> ? THEN DO:
        INPUT FROM VALUE(cRoot + "\usuarios.csv").
        REPEAT :
               IMPORT DELIMITER ";"
                      iId
                      cDesc NO-ERROR.
        
                IF NOT ERROR-STATUS:ERROR THEN DO:
                      
                    CREATE tt-usuario.
                    ASSIGN tt-usuario.idUsuario = iId
                           tt-usuario.cod_usuario =  cDesc.                      
                END.      
        END.
        INPUT CLOSE.    
    END.
    
    ASSIGN iId = ?
           cDesc = "".
           
    IF SEARCH(cRoot + "\programas.csv") <> ? THEN DO:
        INPUT FROM VALUE(cRoot + "\programas.csv").
        REPEAT :
               IMPORT DELIMITER ";"
                      iId
                      cDesc NO-ERROR.
        
                IF NOT ERROR-STATUS:ERROR THEN DO:
                      
                    CREATE tt-programa.
                    ASSIGN tt-programa.idPrograma   = iId
                           tt-programa.cod_programa =  cDesc.
                           
                END.     
        END.
        INPUT CLOSE.    
    END.
END.
/**/

RUN utp/ut-acomp.p PERSISTENT SET h-acomp.
RUN pi-inicializar IN h-acomp ('Aguarde, Processando...').

RUN PeekMessageA(Msg, 0, 0, 0, {&PM_NOREMOVE}, OUTPUT lResult).

RUN pi-usuarios.
RUN pi-programas.
RUN pi-grupos.
RUN pi-grupo-prog.

run pi-acompanhar in h-acomp (input 'Exporta‡äes Concluidas: Finalizando...').

RUN pi-finalizar IN h-acomp.

/*exporta referencias*/
ASSIGN tt-referencias.iUltimaInstancia = iEmpresa
       tt-referencias.cUltimaDescInstancia = cDescInstancia.
       
OUTPUT TO VALUE(cRoot + "\referencias.csv").
FOR FIRST tt-referencias:
    EXPORT DELIMITER ";"
    tt-referencias.    
END. 

OUTPUT CLOSE.
    

MESSAGE 'Exporta‡Æo Concluida e gerada no diret¢rio : ' c-saida
VIEW-AS ALERT-BOX.

PROCEDURE pi-usuarios :
/*------------------------------------------------------------------------------
  Purpose:     
  Parameters:  <none>
  Notes:       
------------------------------------------------------------------------------*/

    DEFINE VARIABLE c_caminhoUsrEmp AS CHARACTER NO-UNDO.

    ASSIGN c_caminho = cRoot + '\usuarios.csv'
           c_caminhoUsrEmp = c-saida + '\usuario_empresa.csv'.
    
    DEFINE VARIABLE iIdUsuario AS INTEGER NO-UNDO.
    DEFINE VARIABLE iRelUsrEmp AS INTEGER NO-UNDO.
 
    //EMPTY TEMP-TABLE tt-usuario.
     

    OUTPUT STREAM A TO VALUE(c_caminho) NO-CONVERT APPEND.
    OUTPUT STREAM B TO VALUE(c_caminhoUsrEmp) NO-CONVERT. 
    
    IF lPrimeiraExec THEN 
        PUT STREAM A UNFORMATTED 
        "z_sga_usuarios_id;cod_usuario;nome_usuario;CPF;cod_gestor;cod_funcao;funcao;email;solicitante;gestor_usuario;gestor_grupo;gestor_modulo;gestor_rotina;gestor_programa;si;idUsrFluig;ativo"    
        SKIP.
        
    PUT STREAM B UNFORMATTED   
        "idUsrEMp;idUsuario;idEmpresa;idGestor" 
         SKIP.
    
    IF AVAILABLE tt-referencias THEN DO:
            ASSIGN iIdUsuario = tt-referencias.iUltimoUsuario
                   iRelUsrEmp = tt-referencias.iUltimoUsrEmpresa .        
    END. 

    
    FOR EACH usuar_mestre
     //   where usuar_mestre.dat_inic_valid <= today
     //     and usuar_mestre.dat_fim_valid  >= today
         NO-LOCK
         BY usuar_mestre.cod_usuario:
         
         FIND FIRST tt-usuario WHERE tt-usuario.cod_usuario =  usuar_mestre.cod_usuario NO-LOCK NO-ERROR.
         IF NOT AVAILABLE tt-usuario THEN DO: 
            
            ASSIGN iIdUsuario = iIdUsuario + 1
                   iRelUsrEmp = iRelUsrEmp + 1 .
                         
             
             CREATE tt-usuario.
             ASSIGN tt-usuario.idUsuario  =  iIdUsuario
                    tt-usuario.cod_usuario =  usuar_mestre.cod_usuario.
    
            run pi-acompanhar in h-acomp (input 'Usuario: ' + usuar_mestre.cod_usuario).
            

            FIND FIRST usuar_mestre_ext WHERE usuar_mestre_ext.cod_usuario  = usuar_mestre.cod_usuario 
                                          AND usuar_mestre_ext.cod_domin_so = "TEGMA" NO-LOCK NO-ERROR .
            IF NOT AVAIL usuar_mestre_ext THEN
               FIND FIRST usuar_mestre_ext WHERE usuar_mestre_ext.cod_usuario  = usuar_mestre.cod_usuario NO-LOCK NO-ERROR .
    
            EXPORT STREAM A
                DELIMITER ';'
                iIdUsuario
                usuar_mestre.cod_usuar                
                REPLACE(REPLACE (usuar_mestre.nom_usuar,chr(10),chr(32)),chr(13),chr(32))
                ''
                'super'
                '1'
                '1'               
                REPLACE(REPLACE (usuar_mestre.cod_e_mail_local,chr(10),chr(32)),chr(13),chr(32))
                'S'
                'N' 
                'N' 
                'N' 
                'N'
                'N'
                'N' 
                IF AVAILABLE usuar_mestre_ext THEN usuar_mestre_ext.cod_usuar_so ELSE ''
                IF usuar_mestre.dat_fim_valid  >= TODAY THEN 1 ELSE 0
                .
                
            EXPORT STREAM B
                DELIMITER ';'
                iRelUsrEmp 
                iIdUsuario      
                iEmpresa.
                
           END. 
           ELSE DO:
                ASSIGN iRelUsrEmp = iRelUsrEmp + 1.
                EXPORT STREAM B
                DELIMITER ';'
                iRelUsrEmp 
                tt-usuario.idUsuario      
                iEmpresa.
           END.
        /**/
    END.
    OUTPUT STREAM B CLOSE.
    OUTPUT STREAM A CLOSE.
    
    ASSIGN tt-referencias.iUltimoUsuario = iIdUsuario
           tt-referencias.iUltimoUsrEmpresa = iRelUsrEmp.


END PROCEDURE.

PROCEDURE pi-grupos :
/*------------------------------------------------------------------------------
  Purpose:     
  Parameters:  <none>
  Notes:       
------------------------------------------------------------------------------*/
    def var c-gestor as char no-undo.
    DEFINE VARIABLE c-saidaGrupo AS CHARACTER NO-UNDO.
    
    DEFINE VARIABLE iIdGrupo  AS INTEGER  NO-UNDO.
    DEFINE VARIABLE iIdGrupos AS INTEGER INIT 1 NO-UNDO.
    

    
    ASSIGN c_caminho    = c-saida + '\grupos.csv'
           c-saidaGrupo = c-saida + '\grupo.csv'.

    EMPTY TEMP-TABLE tt-grupo.
    
    OUTPUT STREAM A TO VALUE(c-saidaGrupo) NO-CONVERT.
    OUTPUT STREAM B TO VALUE(c_caminho) NO-CONVERT.
    
    PUT STREAM A UNFORMATTED "idGrupo;idLegGrupo;descAbrev;descricao;idEmpresa" SKIP.
    PUT STREAM B UNFORMATTED "z_sga_grupos_id;cod_grupo;desc_grupo;gestor;cod_usuario;idGrupo;idUsuario"   SKIP.
    
    ASSIGN iIdGrupo = tt-referencias.iUltimoGrupo
           iIdGrupos = tt-referencias.iUltimoGrpUsr
           .
    
    FOR EACH grp_usuar
             NO-LOCK:
         
        ASSIGN iIdGrupo = iIdGrupo + 1
               . 

        run pi-acompanhar in h-acomp (input 'Grupo: ' + grp_usuar.cod_grp_usuar).
         
        PUT STREAM A 
            UNFORMATTED
            iIdGrupo ";"
            grp_usuar.cod_grp_usuar ";"
            REPLACE(REPLACE (grp_usuar.des_grp_usuar,chr(10),chr(32)),chr(13),chr(32))";"
            ";"
            iEmpresa
            SKIP
            .
            
        CREATE tt-grupo.
        ASSIGN tt-grupo.idGrupo = iIdGrupo
               tt-grupo.cod_grupo = grp_usuar.cod_grp_usuar
               tt-grupo.idInstancia = iEmpresa.

        for each usuar_grp_usuar fields(cod_usuario) no-lock
            where usuar_grp_usuar.cod_grp_usuar = grp_usuar.cod_grp_usuar.

         //   FOR FIRST usuar_mestre FIELDS(dat_fim_valid) NO-LOCK
         //       WHERE usuar_mestre.cod_usuario = usuar_grp_usuar.cod_usuario.
         //   END.

         //   IF AVAIL usuar_Mestre AND
          //     usuar_mestre.dat_fim_valid < TODAY THEN NEXT.
              
            FIND FIRST tt-usuario WHERE tt-usuario.cod_usuario = usuar_grp_usuar.cod_usuario NO-LOCK NO-ERROR.
            IF AVAILABLE tt-usuario THEN DO:
                
            
                ASSIGN  iIdGrupos = iIdGrupos + 1.
    
                EXPORT STREAM B
                    DELIMITER ';'
                    iIdGrupos
                    grp_usuar.cod_grp_usuar                    
                    REPLACE(REPLACE (grp_usuar.des_grp_usuar,chr(10),chr(32)),chr(13),chr(32))
                    'super'
                    usuar_grp_usuar.cod_usuario
                    iIdGrupo
                    tt-usuario.idUsuario
                    .
                    
             END.
                
            // ASSIGN  iIdGrupos = iIdGrupos + 1.   
        end.     
        
        // ASSIGN iIdGrupo = iIdGrupo + 1.   
    END.
    OUTPUT STREAM B CLOSE.
    OUTPUT STREAM A CLOSE.


    ASSIGN  tt-referencias.iUltimoGrupo  = iIdGrupo
            tt-referencias.iUltimoGrpUsr = iIdGrupos
           .
 
END PROCEDURE.

PROCEDURE pi-programas :
/*------------------------------------------------------------------------------
  Purpose:     
  Parameters:  <none>
  Notes:       
------------------------------------------------------------------------------*/
    def var c-desc-rot          AS char no-undo.
    DEFINE VARIABLE iIdPrograma AS INTEGER NO-UNDO.
    DEFINE VARIABLE iRelProgEmp AS INTEGER NO-UNDO.
    
    DEFINE VARIABLE c-saida2    AS CHARACTER NO-UNDO.
    
    ASSIGN c_caminho = cRoot + '\programas.csv'
           c-saida2  = c-saida + '\programa_empresa.csv'
           .

    OUTPUT STREAM A TO VALUE(c_caminho) NO-CONVERT APPEND.
    OUTPUT STREAM B TO VALUE(c-saida2)  NO-CONVERT.
    
    IF lPrimeiraExec THEN 
        PUT STREAM A
            UNFORMATTED 
            "z_sga_programas_id;cod_programa;descricao_programa;cod_modulo;descricao_modulo;especific;upc;obs_upc(helpPrograma);codigo_rotina;descricao_rotina;registro_padrao;visualiza_menu;procedimento_pai"
            SKIP.
    
    PUT STREAM B
        UNFORMATTED
        "idGrupoPrograma;idPrograma;idEmpresa"
        SKIP.
    
   // EMPTY TEMP-TABLE tt-programa.
   
    IF AVAILABLE tt-referencias THEN DO:
            ASSIGN iIdPrograma = tt-referencias.iUltimoPrograma
                   iRelProgEmp = tt-referencias.iUltimoProgEmpresa .        
    END. 
    
    FOR EACH prog_dtsul 
       //WHERE prog_dtsul.log_visualiz_menu 
       NO-LOCK:
             
        run pi-acompanhar in h-acomp (input 'Programa: ' + prog_dtsul.cod_prog_dtsul).

        FIND FIRST tt-programa WHERE tt-programa.cod_programa =  prog_dtsul.cod_prog_dtsul NO-LOCK NO-ERROR.
        IF NOT AVAILABLE tt-programa THEN DO:
            
            ASSIGN  iRelProgEmp = iRelProgEmp + 1
                    iIdPrograma = iIdPrograma + 1.
    
            FIND FIRST aplicat_dtsul OF prog_dtsul   NO-LOCK NO-ERROR.
            FIND FIRST procedimento  OF prog_dtsul   NO-LOCK NO-ERROR.
            FIND FIRST modul_dtsul   OF procedimento NO-LOCK NO-ERROR.
            
            assign c-desc-rot = ''.
            
            case prog_dtsul.idi_tip_prog_dtsul:
                when 1 then
                    assign c-desc-rot = 'Consulta'.
                when 2 then
                    assign c-desc-rot = 'Manuten‡Æo'.
                when 3 then
                    assign c-desc-rot = 'Relat¢rios'.
                when 4 then
                    assign c-desc-rot = 'Tarefas'.
            end.
        
            CREATE tt-programa.
            ASSIGN tt-programa.idPrograma =  iIdPrograma
                   tt-programa.cod_programa =  prog_dtsul.cod_prog_dtsul.
                   
    
            EXPORT STREAM B
                   DELIMITER ";"
                   iRelProgEmp
                   iIdPrograma
                   iEmpresa
                   .    
        
            EXPORT STREAM A 
                DELIMITER ';'
                iIdPrograma
                prog_dtsul.cod_prog_dtsul
                IF prog_dtsul.nom_prog_dtsul = '' THEN procedimento.des_proced ELSE prog_dtsul.nom_prog_dtsul
              //  IF AVAIL aplicat_dtsul THEN aplicat_dtsul.cod_aplicat_dtsul ELSE ''
              //  IF AVAIL aplicat_dtsul THEN aplicat_dtsul.des_aplicat_dtsul ELSE ''
                IF AVAIL modul_dtsul   THEN modul_dtsul.cod_modul_dtsul     ELSE ''
                IF AVAIL modul_dtsul   THEN modul_dtsul.nom_modul_dtsul     ELSE ''
                IF prog_dtsul.log_reg_padr THEN 'N' ELSE 'S'
                REPLACE(REPLACE (prog_dtsul.nom_prog_upc,chr(10),chr(32)),chr(13),chr(32))
                ''
                prog_dtsul.idi_tip_prog_dtsul
                c-desc-rot
                prog_dtsul.log_reg_padr
                prog_dtsul.log_visualiz_menu
                prog_dtsul.cod_proced
                .
                    
        END.
        ELSE DO:
            
            ASSIGN iRelProgEmp = iRelProgEmp + 1.
            EXPORT STREAM B
                   DELIMITER ";"
                   iRelProgEmp
                   tt-programa.idPrograma
                   iEmpresa
                   .
        END.       
           
           

    END.
    OUTPUT STREAM A CLOSE.
    OUTPUT STREAM B CLOSE.
  
    ASSIGN tt-referencias.iUltimoPrograma = iIdPrograma
           tt-referencias.iUltimoProgEmpresa = iRelProgEmp.


END PROCEDURE.

PROCEDURE pi-grupo-prog :
/*------------------------------------------------------------------------------
  Purpose:     
  Parameters:  <none>
  Notes:       
------------------------------------------------------------------------------*/

    def var c-gestor as char no-undo.
    DEFINE VARIABLE iIdRelacao AS INTEGER INITIAL 1 NO-UNDO.

    ASSIGN c_caminho = c-saida + '\grupo_programa.csv'.

    OUTPUT TO VALUE(c_caminho) NO-CONVERT.
   
    
    PUT UNFORMATTED 
        "z_sga_grupo_programa_id;cod_grupo;nome_grupo;gestor;cod_programa;idGrupo;idPrograma"
        SKIP.  

        IF AVAILABLE tt-referencias THEN DO:
                ASSIGN iIdRelacao = tt-referencias.iUltimoGrpProg.        
        END. 
          
        FOR EACH  prog_dtsul_segur FIELDS(cod_prog_dtsul) NO-LOCK,
            FIRST tt-programa FIELDS (tt-programa.idPrograma)
                              WHERE tt-programa.cod_programa = prog_dtsul_segur.cod_prog_dtsul no-lock,
            FIRST tt-grupo WHERE tt-grupo.cod_grupo  = prog_dtsul_segur.cod_grp_usuar 
                             AND tt-grupo.idInstancia = iEmpresa 
                             NO-LOCK:
            
            run pi-acompanhar in h-acomp (input 'Grupo: ' + prog_dtsul_segur.cod_grp_usuar + ' Programa: ' + prog_dtsul_segur.cod_prog_dtsul). 
             
            // FIND FIRST tt-programa WHERE tt-programa.cod_programa = prog_dtsul_segur.cod_prog_dtsul NO-LOCK NO-ERROR .
            ASSIGN iIdRelacao = iIdRelacao + 1.
                
            PUT UNFORMATTED  
                iIdRelacao ';'
                prog_dtsul_segur.cod_grp_usuar ';'
                '' ';'
                'super' ';'
                prog_dtsul_segur.cod_prog_dtsul ';'
                IF AVAILABLE tt-grupo THEN tt-grupo.idGrupo ELSE ? ';'
                tt-programa.idPrograma
                SKIP
                .
            
            RUN PeekMessageA(Msg, 0, 0, 0, {&PM_NOREMOVE}, OUTPUT lResult).
        end.  
              
    
    OUTPUT CLOSE.
    
    run pi-acompanhar in h-acomp (input 'Grupo Programa: Leitura Concluida'). 
    
    ASSIGN tt-referencias.iUltimoGrpProg = iIdRelacao.
   
END PROCEDURE.

PROCEDURE PeekMessageA EXTERNAL "user32.dll" :
    DEFINE INPUT  PARAMETER lpmsg         AS MEMPTR.
    DEFINE INPUT  PARAMETER hWnd          AS LONG.
    DEFINE INPUT  PARAMETER wMsgFilterMin AS LONG.
    DEFINE INPUT  PARAMETER wMsgFilterMax AS LONG.
    DEFINE INPUT  PARAMETER wRemoveMsg    AS LONG.
    DEFINE RETURN PARAMETER lResult       AS LONG.
END PROCEDURE.
