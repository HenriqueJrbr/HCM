<style>
    #load{ display: none};
    h2 small{
        font-size: 13px;
        font-weight: bold;        
        color: #FF8000
    }
    
    .img-load{
        margin: 0 auto;
        width:50%;        
    }
    .box-canvas{
        display: none;
        height: 242px;
        width: 484px;        
    }
    .chartjs-hidden-iframe{
        border: 0px;
        display: block;
        height: 0px;
        left: 0px;
        right: 0px;
        top: 0px;
        bottom: 0px;
        margin: 0px;
        position: absolute;
        width: 100%;
    }
    .fotoColor{
        color: #FF0000 !important
    }
</style>
<script type="text/javascript">
    
    function graficoGrupoMaiorNumUsuariosFoto(){
        var myPromise = new Promise(function(resolve, reject){
            $.ajax({            
                url: url + 'Home/ajaxGruposMaiorNumeroUsuariosFoto',                    
                success: function(res){
                    jsonData = JSON.parse(res);                                    
                    if(jsonData.grupoTop != ''){
                        var ctx = document.getElementById("grupoFoto");
                            var data = {
                                labels: jsonData.grupoTop,
                                datasets: [{
                                    data: jsonData.grupoTopTotal,
                                    backgroundColor: '#FF8000'
                                }]
                            };
                        var canvasDoughnut = new Chart(ctx, {
                            type: 'bar',
                            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                            data: data
                        });

                    } 
                    
                    resolve(1);
                }
            });   
        });
        
        myPromise.then(function(){
            $('#topGrupoMaioNumUsuariosLoadFoto').css('display', 'none');
            $('#grupoFoto').css('display', 'block');
            //graficoUsuariosVsRiscos();
        });             
    }

    function graficoGrupoMaiorNumUsuarios(){
        var myPromise = new Promise(function(resolve, reject){
            $.ajax({            
                url: url + 'Home/ajaxGruposMaiorNumeroUsuarios',                    
                success: function(res){
                    jsonData = JSON.parse(res);                                    
                    if(jsonData.grupoTop != ''){
                        var ctx = document.getElementById("grupo");
                            var data = {
                                labels: jsonData.grupoTop,
                                datasets: [{
                                    data: jsonData.grupoTopTotal,
                                    backgroundColor: '#FF8000'
                                }]
                            };
                        var canvasDoughnut = new Chart(ctx, {
                            type: 'bar',
                            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                            data: data
                        });

                    } 
                    
                    resolve(1);
                }
            });   
        });
        
        myPromise.then(function(){
            $('#topGrupoMaioNumUsuariosLoad').css('display', 'none');
            $('#grupo').css('display', 'block');
            //graficoUsuariosVsRiscos();
        });             
    }
    
    function graficoUsuariosVsRiscosFoto(){
        var myPromise = new Promise(function(resolve, reject){
            $.ajax({            
                url: url + 'Home/ajaxUsuariosVsRiscosFoto',                    
                success: function(res){
                    jsonData = JSON.parse(res);
                    if(jsonData.data != ''){
                        var ctx = document.getElementById("usuarioExpostoFoto");
                        var data = {
                            labels: [
                                'Usuários ativos não expostos ao risco', 'Usuários ativos expostos ao risco'
                            ],
                            datasets: [{
                                data: jsonData.data,
                                backgroundColor: [
                                    '#01DF3A','#FF8000'
                                ]
                            }]
                        };
                        var canvasDoughnut = new Chart(ctx, {
                            type: 'doughnut',
                            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                            data: data
                        }); 
                    }                    
                    
                    resolve(1);
                }
            }); 
        });
        
        myPromise.then(function(value){
            $('#usuariosVsRiscosLoadFoto').css('display', 'none');                  
            $('#usuarioExpostoFoto').css('display', 'block');
            //graficoAreaUsuariosRiscosNaoMitigados();
        });
    }

    function graficoUsuariosVsRiscos(){
        var myPromise = new Promise(function(resolve, reject){
            $.ajax({            
                url: url + 'Home/ajaxUsuariosVsRiscos',                    
                success: function(res){
                    jsonData = JSON.parse(res);
                    if(jsonData.data != ''){
                        var ctx = document.getElementById("usuarioExposto");
                        var data = {
                            labels: [
                                'Usuários ativos não expostos ao risco', 'Usuários ativos expostos ao risco'
                            ],
                            datasets: [{
                                data: jsonData.data,
                                backgroundColor: [
                                    '#01DF3A','#FF8000'
                                ]
                            }]
                        };
                        var canvasDoughnut = new Chart(ctx, {
                            type: 'doughnut',
                            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                            data: data
                        }); 
                    }                    
                    
                    resolve(1);
                }
            }); 
        });
        
        myPromise.then(function(value){
            $('#usuariosVsRiscosLoad').css('display', 'none');                  
            $('#usuarioExposto').css('display', 'block');
            //graficoAreaUsuariosRiscosNaoMitigados();
        });
    }
    
    function graficoAreaUsuariosRiscosNaoMitigadosFoto(){
        var myPromise = new Promise(function(resolve, reject){
            $.ajax({            
                url: url + 'Home/ajaxAreaUsuariosRiscosNaoMitigadosFoto',                    
                success: function(res){
                    jsonData = JSON.parse(res);                                    
                    if(jsonData.areaTop != ''){
                        var ctx = document.getElementById("areaComMaiorPotencialFoto");
                        var data = {
                            labels: jsonData.areaTop,
                            datasets: [{
                                data: jsonData.areaTopTotal,
                                backgroundColor: 
                                    '#FF8000'
                                
                            }]
                        };
                        var canvasDoughnut = new Chart(ctx, {
                            type: 'bar',
                            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                            data: data
                        });
                    } 

                    resolve(1);
                }
            });   
        });
        
        myPromise.then(function(){
            $('#areaUsuariosRiscosNaoMitigadosLoadFoto').css('display', 'none');
            $('#areaComMaiorPotencialFoto').css('display', 'block');
            //graficoRiscosMitigadosNaoMitigados();
        });             
    }

    function graficoAreaUsuariosRiscosNaoMitigados(){
        var myPromise = new Promise(function(resolve, reject){
            $.ajax({            
                url: url + 'Home/ajaxAreaUsuariosRiscosNaoMitigados',                    
                success: function(res){
                    jsonData = JSON.parse(res);                                    
                    if(jsonData.areaTop != ''){
                        var ctx = document.getElementById("areaComMaiorPotencial");
                        var data = {
                            labels: jsonData.areaTop,
                            datasets: [{
                                data: jsonData.areaTopTotal,
                                backgroundColor: 
                                    '#FF8000'
                                
                            }]
                        };
                        var canvasDoughnut = new Chart(ctx, {
                            type: 'bar',
                            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                            data: data
                        });
                    } 

                    resolve(1);
                }
            });   
        });
        
        myPromise.then(function(){
            $('#areaUsuariosRiscosNaoMitigadosLoad').css('display', 'none');
            $('#areaComMaiorPotencial').css('display', 'block');
            //graficoRiscosMitigadosNaoMitigados();
        });             
    }
    
    // Não tem foto
    function graficoRiscosMitigadosNaoMitigados(){
        var myPromise = new Promise(function(resolve, reject){
            $.ajax({            
                url: url + 'Home/ajaxRiscosMitigadosVsNaoMitigados',                    
                success: function(res){
                    jsonData = JSON.parse(res);                    
                    
                    if(jsonData.data.length){                        
                        var ctx = document.getElementById("riscoMitigadosVsNaoMitigados");
                        var data = {
                            labels: ['Riscos mitigados', 'Riscos não mitigados'],
                            datasets: [{
                                data: jsonData.data,
                                backgroundColor: [
                                    '#01DF3A','#FF8000'
                                ]
                            }]
                        };
                        var canvasDoughnut = new Chart(ctx, {
                            type: 'doughnut',
                            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                            data: data
                        });
                    } 

                    resolve(1);
                }
            });   
        });
        
        myPromise.then(function(){
            $('#riscoMitigadosVsNaoMitigadosLoad').css('display', 'none');
            $('#riscoMitigadosVsNaoMitigados').css('display', 'block');
            //graficoProcessosMaisPopulosos();
        });             
    }
    
    function graficoProcessosMaisPopulososFoto(){
        var myPromise = new Promise(function(resolve, reject){
            $.ajax({            
                url: url + 'Home/ajaxProcessosMaisPopulososFoto',                    
                success: function(res){
                    jsonData = JSON.parse(res);                                    
                    if(jsonData.processosTop != ''){
                        var ctx = document.getElementById("processosMaisPopulososFoto");
                        var data = {
                            labels: jsonData.processosTop,
                            datasets: [{
                                data: jsonData.processosTopTotal,
                                backgroundColor: [
                                    '#008FFB', '#00e396', '#feb019', '#ff4560', '#775dd0'
                                ]
                            }]
                        };
                        var canvasDoughnut = new Chart(ctx, {
                            type: 'doughnut',
                            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                            data: data
                        });
                    } 

                    resolve(1);
                }
            });   
        });
        
        myPromise.then(function(){
            $('#processosMaisPopulososLoadFoto').css('display', 'none');
            $('#processosMaisPopulososFoto').css('display', 'block');
            //graficoTopFiveGestoresComMaisRiscos();
        });                 
    }

    function graficoProcessosMaisPopulosos(){
        var myPromise = new Promise(function(resolve, reject){
            $.ajax({            
                url: url + 'Home/ajaxProcessosMaisPopulosos',                    
                success: function(res){
                    jsonData = JSON.parse(res);                                    
                    if(jsonData.processosTop != ''){
                        var ctx = document.getElementById("processosMaisPopulosos");
                        var data = {
                            labels: jsonData.processosTop,
                            datasets: [{
                                data: jsonData.processosTopTotal,
                                backgroundColor: [
                                    '#008FFB', '#00e396', '#feb019', '#ff4560', '#775dd0'
                                ]
                            }]
                        };
                        var canvasDoughnut = new Chart(ctx, {
                            type: 'doughnut',
                            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                            data: data
                        });
                    } 

                    resolve(1);
                }
            });   
        });
        
        myPromise.then(function(){
            $('#processosMaisPopulososLoad').css('display', 'none');
            $('#processosMaisPopulosos').css('display', 'block');
            //graficoTopFiveGestoresComMaisRiscos();
        });                 
    }
    
    function graficoTopFiveGestoresComMaisRiscosFoto(){
        var myPromise = new Promise(function(resolve, reject){
            $.ajax({            
                url: url + 'Home/ajaxTopFiveGestoresComMaisRiscosFoto',
                success: function(res){
                    jsonData = JSON.parse(res);                                    
                    if(jsonData.gestorTop != ''){
                        var ctx = document.getElementById("top5GestoresComMaisRiscosFoto");
                        var data = {
                            labels: jsonData.gestorTop,
                            datasets: [{
                                data: jsonData.gestorTopTotal,
                                backgroundColor: [
                                    '#008FFB', '#00e396', '#feb019', '#ff4560', '#775dd0'
                                ]
                            }]
                        };

                        var canvasDoughnut = new Chart(ctx, {
                            type: 'pie',
                            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                            data: data,
                            options: {
                                title: {
                                    display: false,
                                    text: 'Chart.js Line Chart - Animation Progress Bar'
                                }
                            }
                        });
                    } 

                    resolve(1);
                }
            });   
        });
        
        myPromise.then(function(){
            $('#top5GestoresComMaisRiscosLoadFoto').css('display', 'none');
            $('#top5GestoresComMaisRiscosFoto').css('display', 'block');
           //graficoRiscosEmPotencial();
        });         
    }

    function graficoTopFiveGestoresComMaisRiscos(){
        var myPromise = new Promise(function(resolve, reject){
            $.ajax({            
                url: url + 'Home/ajaxTopFiveGestoresComMaisRiscos',                    
                success: function(res){
                    jsonData = JSON.parse(res);                                    
                    if(jsonData.gestorTop != ''){
                        var ctx = document.getElementById("top5GestoresComMaisRiscos");
                        var data = {
                            labels: jsonData.gestorTop,
                            datasets: [{
                                data: jsonData.gestorTopTotal,
                                backgroundColor: [
                                    '#008FFB', '#00e396', '#feb019', '#ff4560', '#775dd0'
                                ]
                            }]
                        };

                        var canvasDoughnut = new Chart(ctx, {
                            type: 'pie',
                            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                            data: data,
                            options: {
                                title: {
                                    display: false,
                                    text: 'Chart.js Line Chart - Animation Progress Bar'
                                }
                            }
                        });
                    } 

                    resolve(1);
                }
            });   
        });
        
        myPromise.then(function(){
            $('#top5GestoresComMaisRiscosLoad').css('display', 'none');
            $('#top5GestoresComMaisRiscos').css('display', 'block');
           //graficoRiscosEmPotencial();
        });         
    }
    
    function graficoRiscosEmPotencialFoto(){
        var myPromise = new Promise(function(resolve, reject){
            $.ajax({            
                url: url + 'Home/ajaxRiscosEmPotencialFoto',
                success: function(res){
                    jsonData = JSON.parse(res);                                    
                    if(jsonData.riscosTop != ''){
                        var ctx = document.getElementById("riscosEmPotencialFoto");
                        var data = {
                            labels: jsonData.riscosTop,
                            datasets: [{
                                data: jsonData.riscosTopTotal,
                                backgroundColor: [
                                    '#008FFB', '#00e396', '#feb019', '#ff4560', '#775dd0'
                                ]
                            }]
                        };
                        var canvasDoughnut = new Chart(ctx, {
                            type: 'bar',
                            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                            data: data
                        });
                    } 

                    

                    resolve(1);
                }
            });   
        });
                
        myPromise.then(function(){
            $('#riscosEmPotencialLoadFoto').css('display', 'none');
            $('#riscosEmPotencialFoto').css('display', 'block');
            //graficoRiscosMitigadosNaoMitigados();
        });         
    }

    function graficoRiscosEmPotencial(){
        var myPromise = new Promise(function(resolve, reject){
            $.ajax({            
                url: url + 'Home/ajaxRiscosEmPotencial',                    
                success: function(res){
                    jsonData = JSON.parse(res);                                    
                    if(jsonData.riscosTop != ''){
                        var ctx = document.getElementById("riscosEmPotencial");
                        var data = {
                            labels: jsonData.riscosTop,
                            datasets: [{
                                data: jsonData.riscosTopTotal,
                                backgroundColor: [
                                    '#008FFB', '#00e396', '#feb019', '#ff4560', '#775dd0'
                                ]
                            }]
                        };
                        var canvasDoughnut = new Chart(ctx, {
                            type: 'bar',
                            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                            data: data
                        });
                    } 

                    

                    resolve(1);
                }
            });   
        });
                
        myPromise.then(function(){
            $('#riscosEmPotencialLoad').css('display', 'none');
            $('#riscosEmPotencial').css('display', 'block');
            //graficoRiscosMitigadosNaoMitigados();
        });         
    }
    
    $(document).ready(function(){
        // Carrega os gráficos
        graficoGrupoMaiorNumUsuariosFoto()
        graficoGrupoMaiorNumUsuarios();
        graficoUsuariosVsRiscosFoto();
        graficoUsuariosVsRiscos();
        graficoAreaUsuariosRiscosNaoMitigadosFoto();
        graficoAreaUsuariosRiscosNaoMitigados();
        //graficoRiscosMitigadosNaoMitigados();
        graficoProcessosMaisPopulososFoto();
        graficoProcessosMaisPopulosos();
        //graficoTopFiveGestoresComMaisRiscosFoto();
        //graficoTopFiveGestoresComMaisRiscos();
        //graficoRiscosEmPotencialFoto();
        //graficoRiscosEmPotencial();
        
    });
</script>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">        
        <li class="active">Dashboard</li>
    </ol>
</div>

<div class="row tile_count">
    <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total de Usuários</span>
        <div class="count"><?php echo $contaUsuarioTotal[0] ?></div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total de Usuários Ativos</span>
        <div class="count"><?php echo $contUsuario[0] ?></div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total de Usuários Inativos</span>
        <div class="count"><?php echo $contUsuarioInativo[0] ?></div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-users"></i> Total de Grupos</span>
        <div class="count"><?php echo $contGrupo[0] ?></div>
    </div>

</div>
<div class="row tile_count">
    <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-clock-o"></i> Total de Programas</span>
        <div class="count"><?php echo $contPrograma[0] ?></div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-clock-o"></i> Usuários Ativos Exposto a Risco</span>
        <div class="count"><?php echo $TotalexpostoRisco['expostoArisco'] ?></div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-clock-o"></i> Usuários Inativos Exposto a Risco</span>
        <div class="count"><?php echo $TotalexpostoRiscoInativo['expostoArisco'] ?></div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Top 10 <small>- Grupos com maior número de usuários <span class="fotoColor">(snapshot)</span></small> </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
                        
            <div class="x_content" id="topGrupoMaioNumUsuariosBoxSnapshot">
                <img src="<?php echo URL ?>/assets/images/down.gif" class="img-responsive img-load" id="topGrupoMaioNumUsuariosLoadFoto">
                <iframe class="chartjs-hidden-iframe"></iframe>
                <canvas id="grupoFoto" class="box-canvas"></canvas>
                <a href="<?php echo URL; ?>/Grupo"><span class="badge"><i class="fa fa-arrow-circle-o-right"></i> Ir para grupos</a> </span>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Top 10 <small>- Grupos com maior número de usuários</small> </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
                        
            <div class="x_content" id="topGrupoMaioNumUsuariosBox">
                <img src="<?php echo URL ?>/assets/images/down.gif" class="img-responsive img-load" id="topGrupoMaioNumUsuariosLoad">
                <iframe class="chartjs-hidden-iframe"></iframe>
                <canvas id="grupo" class="box-canvas"></canvas>
                <a href="<?php echo URL; ?>/Grupo"><span class="badge"><i class="fa fa-arrow-circle-o-right"></i> Ir para grupos</a> </span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Usuários Ativos vs Riscos <small><span class="fotoColor">(snapshot)</span></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content" id="usuariosVsRiscosBoxFoto">
                <img src="<?php echo URL ?>/assets/images/down.gif" class="img-responsive img-load" id="usuariosVsRiscosLoadFoto">
                <iframe class="chartjs-hidden-iframe"></iframe>
                <canvas id="usuarioExpostoFoto" class="box-canvas"></canvas>
                <a href="<?php echo URL; ?>/Usuario"><span class="badge"><i class="fa fa-arrow-circle-o-right"></i> Ir para usuários</a> </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Usuários Ativos vs Riscos</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content" id="usuariosVsRiscosBox">
                <img src="<?php echo URL ?>/assets/images/down.gif" class="img-responsive img-load" id="usuariosVsRiscosLoad">
                <iframe class="chartjs-hidden-iframe"></iframe>
                <canvas id="usuarioExposto" class="box-canvas"></canvas>
                <a href="<?php echo URL; ?>/Usuario"><span class="badge"><i class="fa fa-arrow-circle-o-right"></i> Ir para usuários</a> </span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Área <small>- Usuários riscos não mitigados <span class="fotoColor">(snapshot)</span></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content" id="areaUsuariosRiscosNaoMitigadosBoxFoto">
                <img src="<?php echo URL ?>/assets/images/down.gif" class="img-responsive img-load" id="areaUsuariosRiscosNaoMitigadosLoadFoto">
                <iframe class="chartjs-hidden-iframe"></iframe>
                <canvas id="areaComMaiorPotencialFoto" class="box-canvas"></canvas>
                <a href="<?php echo URL; ?>/Matriz/cadastroArea"><span class="badge"><i class="fa fa-arrow-circle-o-right"></i> Ir para áreas</a> </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Área <small>- Usuários riscos não mitigados</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content" id="areaUsuariosRiscosNaoMitigadosBox">
                <img src="<?php echo URL ?>/assets/images/down.gif" class="img-responsive img-load" id="areaUsuariosRiscosNaoMitigadosLoad">
                <iframe class="chartjs-hidden-iframe"></iframe>
                <canvas id="areaComMaiorPotencial" class="box-canvas"></canvas>
                <a href="<?php echo URL; ?>/Matriz/cadastroArea"><span class="badge"><i class="fa fa-arrow-circle-o-right"></i> Ir para áreas</a> </span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Processos mais populosos <small><span class="fotoColor">(snapshot)</span></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content" id="processosMaisPopulososBoxFoto">
                <img src="<?php echo URL ?>/assets/images/down.gif" class="img-responsive img-load" id="processosMaisPopulososLoadFoto">
                <iframe class="chartjs-hidden-iframe"></iframe>
                <canvas id="processosMaisPopulososFoto" class="box-canvas"></canvas>
                <a href="<?php echo URL; ?>/Matriz/cadastroProcesso"><span class="badge"><i class="fa fa-arrow-circle-o-right"></i> Ir para processos</a> </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Processos mais populosos</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content" id="processosMaisPopulososBox">
                <img src="<?php echo URL ?>/assets/images/down.gif" class="img-responsive img-load" id="processosMaisPopulososLoad">
                <iframe class="chartjs-hidden-iframe"></iframe>
                <canvas id="processosMaisPopulosos" class="box-canvas"></canvas>
                <a href="<?php echo URL; ?>/Matriz/cadastroProcesso"><span class="badge"><i class="fa fa-arrow-circle-o-right"></i> Ir para processos</a> </span>
            </div>
        </div>
    </div>
</div>
<!--<div class="row">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Riscos <small>- Mitigados vs não mitigados</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content" id="riscoMitigadosVsNaoMitigadosBox">
                <img src="<?php echo URL ?>/assets/images/down.gif" class="img-responsive img-load" id="riscoMitigadosVsNaoMitigadosLoad">
                <iframe class="chartjs-hidden-iframe"></iframe>
                <canvas id="riscoMitigadosVsNaoMitigados" class="box-canvas"></canvas>
                <a href="<?php echo URL; ?>/Matriz/cadastroDeRisco"><span class="badge"><i class="fa fa-arrow-circle-o-right"></i> Ir para riscos</a> </span>
            </div>
        </div>
    </div>       
</div>-->


<!--<div class="row">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Gestor <small>- Usuários riscos não mitigados <span class="colorFoto">(snaphost)</span></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content" id="top5GestoresComMaisRiscosBoxFoto">
                <img src="<?php echo URL ?>/assets/images/down.gif" class="img-responsive img-load" id="top5GestoresComMaisRiscosLoadFoto">
                <iframe class="chartjs-hidden-iframe"></iframe>
                <canvas id="top5GestoresComMaisRiscosFoto" class="box-canvas"></canvas>
                <a href="<?php echo URL; ?>/usuario"><span class="badge"><i class="fa fa-arrow-circle-o-right"></i> Ir para usuários</a> </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Gestor <small>- Usuários riscos não mitigados</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content" id="top5GestoresComMaisRiscosBox">
                <img src="<?php echo URL ?>/assets/images/down.gif" class="img-responsive img-load" id="top5GestoresComMaisRiscosLoad">
                <iframe class="chartjs-hidden-iframe"></iframe>
                <canvas id="top5GestoresComMaisRiscos" class="box-canvas"></canvas>
                <a href="<?php echo URL; ?>/usuario"><span class="badge"><i class="fa fa-arrow-circle-o-right"></i> Ir para usuários</a> </span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Riscos em potencial <small><span class="colorFoto">(snapshot)</span></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content" id="riscosEmPotencialBoxFoto">
                <img src="<?php echo URL ?>/assets/images/down.gif" class="img-responsive img-load" id="riscosEmPotencialLoadFoto">
                <iframe class="chartjs-hidden-iframe"></iframe>
                <canvas id="riscosEmPotencialFoto" class="box-canvas" ></canvas>
                <a href="<?php echo URL; ?>/matriz/cadastroDeRisco"><span class="badge"><i class="fa fa-arrow-circle-o-right"></i> Ir para riscos</a> </span>
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Riscos em potencial</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content" id="riscosEmPotencialBox">
                <img src="<?php echo URL ?>/assets/images/down.gif" class="img-responsive img-load" id="riscosEmPotencialLoad">
                <iframe class="chartjs-hidden-iframe"></iframe>
                <canvas id="riscosEmPotencial" class="box-canvas" ></canvas>
                <a href="<?php echo URL; ?>/matriz/cadastroDeRisco"><span class="badge"><i class="fa fa-arrow-circle-o-right"></i> Ir para riscos</a> </span>
            </div>
        </div>
    </div> 
 </div>-->



