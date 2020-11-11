</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FIM CENTRO DA CARGA -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                <!--SGA - 2018 V.2.01-->
                Sistema de Gestão de Acessos (SGA) &copy; 2019 <a href="http://www.bitistech.com.br" target="blanck"> www.bitistech.com.br</a> | versão: <strong> <?php include("versao")?> </strong>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

    <script src="<?php echo URL ?>/assets/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo URL ?>/assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo URL ?>/assets/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="<?php echo URL ?>/assets/vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="<?php echo URL ?>/assets/vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="<?php echo URL ?>/assets/vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="<?php echo URL ?>/assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="<?php echo URL ?>/assets/vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="<?php echo URL ?>/assets/vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="<?php echo URL ?>/assets/vendors/Flot/jquery.flot.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/Flot/jquery.flot.pie.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/Flot/jquery.flot.time.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/Flot/jquery.flot.stack.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="<?php echo URL ?>/assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="<?php echo URL ?>/assets/vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="<?php echo URL ?>/assets/vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="<?php echo URL ?>/assets/vendors/moment/min/moment.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>


    <script src="<?php echo URL ?>/assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/jszip/dist/jszip.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/pdfmake/build/vfs_fonts.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
    <script src="<?php echo URL ?>/assets/build/js/select2.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/jquery-form/jquery.form.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="<?php echo URL ?>/assets/build/js/custom.min.js"></script>
    <script src="<?php echo URL ?>/assets/js/script.js"></script>



    <script>
        $(document).ready(function(){
            
            setInterval(function(){  

                $.ajax({
                    type: "POST",
                    url: url+"Home/ajaxNotificacao",
                    data:'id='+"",
                    beforeSend: function(){           
                    },
                    success: function(data){
                        var dados = JSON.parse(data);
                        $(".quantAtiv").html(dados.length);
                     
                        
                        var html = "";
                        for (var i=0;i<dados.length; i++){
                            //console.log(dados[i].descricao);
                            html+="<li>";
                                html+="<a href='"+url+"Fluxo/callRegras/"+dados[i].url+"/"+dados[i].idSolicitacao+"/"+dados[i].idAtividade+"/0/"+dados[i].idMovimentacao+"/"+dados[i].idSolicitante+"'";
                                    
                                    html+="<span>";
                                        html+="<span><strong>Olá!</strong> "+dados[i].nome_usuario+"</span>";
                                    html+="</span>";
                                   
                                    html+="<p>";      
                                        html+="Você tem esta tarefa pendente.<br>";
                                        html+="Descrição: <strong>"+dados[i].descricao+"</strong><br>";
                                        html+="Numero da Solicitação: <strong>"+dados[i].idSolicitacao+"</strong>";
                                    html+="</p>";
                                  
                                html+="</a>";
                            html+="</li>";                             
                        }
                        $(".atividade").html(html);  
                    }
                });

            }, 3000);
            
            $(document).on('click', '.iconeFavorito', function(event){
                var id = $(this).attr('id');
                var nId = '';

                if (id.length == 11) {
                    nId = id[10];
                } else if (id.length == 12) {
                    nId = id[10] + id[11];
                } else {
                    nId = id;
                }

                id = nId;

                $('#load').css('display', 'block');
               
                $.post(
                    url + 'Home/favoritar',
                    {idMenu:id, idUsuario:<?php echo $_SESSION['idUsrTotvs']?>},

                    function(data, textStatus, xhr) {
                        $('#load').css('display', 'none');
                        
                        var isFavorite = $.parseJSON(data).isFavorite;

                        // Favoritou
                        if (isFavorite == 1) {
                            $('#' + id).removeClass('fa-star-o').addClass('fa-star');

                            // Troca a cor
                            $('#' + id).removeClass('naoFavoritado').addClass('favoritado');

                            cat = $('#' + id).parent().parent().prev().text().replace(/ /g, '');

                            if ($('#menuDeFavoritados').children('#' + cat).length > 0) {
                                // Adiciona aos favoritados
                                $(
                                    '<li class="float-left" id="favoritado' + id + '">' +
                                        $('#menuFavorito' + id).html() + 
                                    '</li>'
                                ).insertAfter('#' + cat);
                            } else {
                                console.log(cat);
                                // Adiciona aos favoritados
                                $('#menuDeFavoritados').append(
                                    '<li class="mt-1 row catFavoritos" id="' + cat.replace(/ /g, '') + '">' +
                                        cat +
                                    '</li>' +
                                    '<li class="float-left" id="favoritado' + id + '">' +
                                    $('#menuFavorito' + id).html() +
                                    '</li>'
                                );
                            }

                            // Muda o id
                            $('#menuDeFavoritados > li').children('#' + id).prop('id', 'favoritado' + id);
                        } 
                        // Desfavoritou
                        else {
                            $('#' + id).removeClass('fa-star').addClass('fa-star-o');

                            $('#' + id).removeClass('favoritado');
                            $('#' + id).addClass('naoFavoritado');


                            var cat = $('#favoritado' + id).prev().hasClass('catFavoritos');
                            var haveMenuAbove = $('#favoritado' + id).next().children().first().hasClass('iconeFavorito');

                            if (cat == true && haveMenuAbove == false) {
                                $('#favoritado' + id).prev().remove();
                                $('#menuDeFavoritados').children('#favoritado' + id).remove();
                            } else {
                                $('#menuDeFavoritados').children('#favoritado' + id).remove();
                            }

                        } 
                    }
                );
            });
            // .done(function() {
            //     console.log("success");
            // })
            // .fail(function() {
            //     console.log("error");
            // })
            // .always(function() {
            //     console.log("complete");
            // });
            
            /* Act on the event */
        });
            
        // Alterna entre logos quando botão de menu é clicado
        $('#menu_toggle').on('click', function () {
            $('.navbar').find('.img-logo-menu-big').toggleClass('hide')
            $('.navbar').find('.img-logo-menu-small').toggleClass('hide');
        });




        /* ControlSidebar()
         * ===============
         * Toggles the state of the control sidebar
         *
         * @Usage: $('#control-sidebar-trigger').controlSidebar(options)
         *         or add [data-toggle="control-sidebar"] to the trigger
         *         Pass any option as data-option="value"
         */
        +function ($) {
            'use strict';

            //var DataKey = 'lte.controlsidebar';
            var DataKey = 'controlsidebar';

            var Default = {
                controlsidebarSlide: true
            };

            var Selector = {
                sidebar: '.control-sidebar',
                data   : '[data-toggle="control-sidebar"]',
                open   : '.control-sidebar-open',
                bg     : '.control-sidebar-bg',
                wrapper: '.wrapper',
                content: '.content-wrapper',
                boxed  : '.layout-boxed'
            };

            var ClassName = {
                open: 'control-sidebar-open',
                transition: 'control-sidebar-hold-transition',
                fixed: 'fixed'
            };

            var Event = {
                collapsed: 'collapsed.controlsidebar',
                expanded : 'expanded.controlsidebar'
            };

            // ControlSidebar Class Definition
            // ===============================
            var ControlSidebar = function (element, options) {
                this.element         = element;
                this.options         = options;
                this.hasBindedResize = false;

                this.init();
            };

            ControlSidebar.prototype.init = function () {
                // Add click listener if the element hasn't been
                // initialized using the data API
                if (!$(this.element).is(Selector.data)) {
                    $(this).on('click', this.toggle);
                }

                this.fix();
                $(window).resize(function () {
                    this.fix();
                }.bind(this));
            };

            ControlSidebar.prototype.toggle = function (event) {
                if (event) event.preventDefault();

                this.fix();

                if (!$(Selector.sidebar).is(Selector.open) && !$('body').is(Selector.open)) {
                    this.expand();
                } else {
                    this.collapse();
                }
            };

            ControlSidebar.prototype.expand = function () {
                $(Selector.sidebar).show();
                if (!this.options.controlsidebarSlide) {
                    $('body').addClass(ClassName.transition).addClass(ClassName.open).delay(50).queue(function(){
                        $('body').removeClass(ClassName.transition);
                        $(this).dequeue()
                    })
                } else {
                    $(Selector.sidebar).addClass(ClassName.open);
                }


                $(this.element).trigger($.Event(Event.expanded));
            };

            ControlSidebar.prototype.collapse = function () {
                if (!this.options.controlsidebarSlide) {
                    $('body').addClass(ClassName.transition).removeClass(ClassName.open).delay(50).queue(function(){
                        $('body').removeClass(ClassName.transition);
                        $(this).dequeue()
                    })
                } else {
                    $(Selector.sidebar).removeClass(ClassName.open);
                }
                $(Selector.sidebar).fadeOut();
                $(this.element).trigger($.Event(Event.collapsed));
            };

            ControlSidebar.prototype.fix = function () {
                if ($('body').is(Selector.boxed)) {
                    this._fixForBoxed($(Selector.bg));
                }
            };

            // Private

            ControlSidebar.prototype._fixForBoxed = function (bg) {
                bg.css({
                    position: 'absolute',
                    height  : $(Selector.wrapper).height()
                });
            };

            // Plugin Definition
            // =================
            function Plugin(option) {
                return this.each(function () {
                    var $this = $(this);
                    var data  = $this.data(DataKey);

                    if (!data) {
                        var options = $.extend({}, Default, $this.data(), typeof option == 'object' && option);
                        $this.data(DataKey, (data = new ControlSidebar($this, options)));
                    }

                    if (typeof option == 'string') data.toggle();
                });
            }

            var old = $.fn.controlSidebar;

            $.fn.controlSidebar             = Plugin;
            $.fn.controlSidebar.Constructor = ControlSidebar;

            // No Conflict Mode
            // ================
            $.fn.controlSidebar.noConflict = function () {
                $.fn.controlSidebar = old;
                return this;
            };

            // ControlSidebar Data API
            // =======================
            $(document).on('click', Selector.data, function (event) {
                if (event) event.preventDefault();
                Plugin.call($(this), 'toggle');
            });
        }(jQuery);

        $(function(){
            $('[data-toggle="control-sidebar"]').controlSidebar()
            var $controlSidebar = $('[data-toggle="control-sidebar"]').data('controlsidebar')
            $(window).on('load', function() {
                // Reinitialize variables on load
                $controlSidebar = $('[data-toggle="control-sidebar"]').data('.controlsidebar');
            })
        });

        function favorite(id, isFavorite) {
            $.ajax({
                url: url + 'Home/favoritar',
                type: 'POST',
                dataType: 'json',
                data: {
                    favoritar: isFavorite,
                    idMenu: id
                },

                success: function (resp, status) {
                    // Remove uma classe e substitui por outra
                    if ($('#' + id).hasClass('fa-star-o')) {
                        $('#' + id).remove('fa-star-o');
                        $('#' + id).addClass('fa-star');
                    } else {
                        $('#' + id).remove('fa-star');
                        $('#' + id).addClass('fa-star-o');
                    }

                    var content = $('#menu' + id).html();

                    $('.favoritos').append(content);

                }
            }).fail(function (resp, status) {
                

                console.log(resp);
            })
        }
    </script>





</body>
</html>