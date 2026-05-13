window.THREE = require('three'); 

require('../../OrbitControls.js')
require('../../OBJLoader.js')
require('../../MTLLoader.js')
require('../../TrackballControls.js')

var Detector = require('../../Detector');

function ModelLabelsCtrl($scope, $filter, ModelLabel, ClientPair, Client, ScanSession, User) {
    // standard global variables
    var container, scene, camera, renderer, controls;
    var mouseX, mouseY, windowHalfX, windowHalfY;
    var backgroundScene, backgroundCamera, backgroundTexture, backgroundMesh;
    var cameraX, cameraY, cameraZ, cameraInterval;
    var raycaster, mouse;
    var SCREEN_WIDTH, SCREEN_HEIGHT;
    
    // custom global variables
    var modelContainer, modelObjectPath, modelTexturePath, modelTarget;
    var objects = [];
    var pointsObjects = [];
    var highlightedObjects = [];
    var tempObjects = [];
    var inactiveColor = "rgb(255,0,0)";
    var activeColor = "rgb(0,12,255)";

    // scene
    scene = new THREE.Scene();
    
    // load fonts
    var loader = new THREE.FontLoader();
    loader.load('/fonts/gentilis_regular.typeface.json', function ( font ) {
        init( font );
        animate();
    });

    this.search = { 
        params: { scan_type: 'body_scan', target: 'female', text: '' },
        meta: { currentPage: 1, perPage: 5, startPos: 0, loaded: false },
        results: [],
        displayedResults: [],
        perPageOptions: [1, 3, 5, 10, 25, 50, 100]
    }
    this.bookmark = {}
    this.showBookmark = false

    this.guided_scan = false
    if ($("#guided").data("value") == true || $("#guided").data("value") == 'true') {
        this.guided_scan = true
    }

    if ($("#scanType").data("value") != 'body_scan') {
        this.search.params.scan_type = 'chakra_scan'
    }

    if ($("#modelTarget").data("value") != undefined) {
        this.search.params.target = $("#modelTarget").data("value")
    }

    perPage = parseInt($("#perPage").data("value"))
    if (!isNaN(perPage) && Number.isInteger(perPage) && this.search.perPageOptions.includes(perPage)) {
        this.search.meta.perPage = parseInt($("#perPage").data("value"))
    }

    page = parseInt($("#page").data("value"))
    if (!isNaN(page) && Number.isInteger(page)) {
        this.search.meta.currentPage = page
        this.search.meta.startPos = this.search.meta.perPage * (this.search.meta.currentPage - 1)
    }

    _this = this
    User.prototype.Me.bookmarks( function(bookmarks) {
        _this.bookmarks = bookmarks
    })

    if ($("#clientId").data("value") != undefined) {
        _this = this
        Client.get({ id: $("#clientId").data("value")  }, function(client){
            _this.client = client
            if ($("#modelTarget").data("value") != client.gender) {
                _this.search.params.target = client.gender
            }
        })
    } else {
        _this = this
        _this.client = null
        Client.query(function(clients){
            _this.clients = clients
        }) 
    }

    _this.scan_session_id = $("#ssid").data("value");
    if (_this.scan_session_id != '') {
        _this.disable_client_selection = true
    } else {
        _this.disable_client_selection = false;
        _this.scan_session_id = null
    }

    _this.setOrderBy = function(guided) {
        _this.orderBy = 'point.name'
        if (guided) { _this.orderBy = ['point.guidedReferenceNumber', 'point.name'] }
        return _this.orderBy
    }

    $scope.bookmark_record = { name: '' }
    _this.saveBookmark = function(bookmark) {
        _this = this

        if (bookmark.id == undefined) {
            params = { target: _this.search.params.target }
            if (_this.scan_session_id != undefined && _this.scan_session_id != '') {
                params = angular.extend(params, { ssid: _this.scan_session_id })
            }
            if (_this.guided_scan == true) {
                params = angular.extend(params, { guided: _this.guided_scan })
            }
            if (_this.search.meta.currentPage > 1) {
                params = angular.extend(params, { page: _this.search.meta.currentPage })
            }
            if (_this.search.meta.perPage != 5) {
                params = angular.extend(params, { perPage: _this.search.meta.perPage })
            }
            queryString = Object.keys(params).map(key => key + '=' + params[key]).join('&');
            url = window.location.pathname + '?' + queryString

            data = { name: bookmark.name, url: url }
            User.prototype.Me.create_bookmark(data, function(bookmark){
                _this.bookmarks.push(bookmark)
                _this.clearBookmark()
            })
        } else {
            User.prototype.Me.update_bookmark($scope.bookmark_record, function(_bookmark){
                index = _this.bookmarks.indexOf(bookmark)
                _this.bookmarks.splice(index, 1)
                _this.bookmarks.push(_bookmark)
                _this.clearBookmark()
            })
        }
        
    }

    _this.editBookmark = function(bookmark) {
        $scope.bookmark_record = bookmark
    }

    _this.clearBookmark = function(bookmark) {
        $scope.bookmark_record = { name: '' }
    }

    _this.deleteBookmark = function(bookmark) {
        var confirmDialog = confirm("Are you sure you wish to delete this bookmark? / ¿Está seguro de que desea eliminar este marcador?");
        if (confirmDialog == true) {
            User.prototype.Me.delete_bookmark({ id: bookmark.id}, function(_bookmark){
                index = _this.bookmarks.indexOf(bookmark)
                _this.bookmarks.splice(index, 1)
            })
        }
    }

    ModelLabel.query(this.search.params, (function(_this) {
        return function(results) {
            if (results.length > 0) {
                var filteredResults = $filter('orderBy')(results, _this.setOrderBy($("#guided").data("value")))
                filteredResults = $filter('unique')(filteredResults, 'point.name')
                filteredResults = $filter('limitTo')(filteredResults, _this.search.meta.perPage)
                filteredResults = $filter('slice')(filteredResults, _this.search.meta.startPos, _this.search.meta.startPos+_this.search.meta.perPage)

                angular.forEach(filteredResults, function(result, index) {
                    points = $filter('filter')(results, { point: { id: result.point.id, name: result.point.name } })

                    angular.forEach(points, function(point, index) {
                        renderPoint(point, pointsObjects, inactiveColor)
                    });
                });

                _this.search.meta.totalPages = Math.floor(results.length / _this.search.meta.perPage);
                _this.search.results = results
                _this.setPaginationText()

                if (_this.scan_session_id != undefined) {
                    ScanSession.get({ id: _this.scan_session_id }, function(scan_session) {
                        _this.client = scan_session.client
                        _this.client.scan_session_id = scan_session.id
                        _this.search.params.target = scan_session.client.gender

                        pairIds = scan_session.pairIds
                        angular.forEach(_this.search.results, function(result) {
                            if (result.point != null) {
                                result.point._delete = false
                                if (pairIds != null && pairIds.includes(result.point.id)) { 
                                    result.point._delete = true 
                                }
                            }
                        });
                    });
                } else {
                    if (_this.client != undefined && _this.scan_session_id == undefined) {
                        Client.prototype.ScanSession.latest({ client_id: _this.client.id, scan_type: _this.search.params.scan_type }, function(scan_session) {
                            if (scan_session != null) {
                                pairIds = scan_session.pairIds
                                _this.client.scan_session_id = scan_session.id
                            } else {
                                pairIds = null
                                _this.client.scan_session_id = null
                            }
    
                            angular.forEach(_this.search.results, function(result) {
                                if (result.point != null) {
                                    result.point._delete = false
                                    if (pairIds != null && pairIds.includes(result.point.id)) { 
                                        result.point._delete = true 
                                    }
                                }
                            });
                        })
                    }
                }
            }
            _this.search.meta.loaded = true;
            return _this.model_labels = results;
        };
    })(this));

    var watchPerPage, watchPerPageAction;
    watchPerPage = (function(_this) {
        return function() {
            return _this.search.meta.perPage;
        };
    })(this);
    watchPerPageAction = (function(_this) {
        return function(val, oldValue) {
            if (val != oldValue) {
                pageCount = Math.floor(_this.search.results.length / val)
                if (pageCount < 1) { pageCount = 1 }
                _this.search.meta.totalPages = pageCount
                
                removeSceneObjects(pointsObjects)

                $("#prevPoint").attr("disabled", "true");
                $("#nextPoint").removeAttr("disabled");
                _this.search.meta.startPos = 0
                _this.search.meta.currentPage = 1

                _this.reloadPoints();
                _this.setPaginationText()
            }
        };
    })(this);
    $scope.$watch(watchPerPage, watchPerPageAction);

    var watchSearchText, watchSearchTextAction;
    watchSearchText = (function(_this) {
        return function() {
            return _this.search.params.text;
        };
    })(this);
    watchSearchTextAction = (function(_this) {
        return function(val, oldValue) {
            if (val != oldValue) {
                pageCount = Math.floor(_this.search.results.length / val)
                if (pageCount < 1) { pageCount = 1 }
                _this.search.meta.totalPages = pageCount

                $("#prevPoint").attr("disabled", "true");
                $("#nextPoint").removeAttr("disabled");
                _this.search.meta.startPos = 0
                _this.search.meta.currentPage = 1

                _this.reloadPoints();
                _this.setPaginationText()
            }
        };
    })(this);
    $scope.$watch(watchSearchText, watchSearchTextAction);

    var watchClient, watchClientAction;
    watchClient = (function(_this) {
        return function() {
            return _this.client;
        };
    })(this);
    watchClientAction = (function(_this) {
        return function(val, oldValue) {
            if (_this.scan_session_id != undefined) { return }

            if (val != oldValue && val != null) {
                Client.get({ id: val.id }, function(client) {
                    Client.prototype.ScanSession.latest({ client_id: client.id, scan_type: _this.search.params.scan_type }, function(scan_session) {
                        if (scan_session != null) {
                            pairIds = scan_session.pairIds
                            _this.client.scan_session_id = scan_session.id
                        } else {
                            pairIds = null
                            _this.client.scan_session_id = null
                        }

                        angular.forEach(_this.search.results, function(result) {
                            if (result.point != null) {
                                result.point._delete = false
                                if (pairIds != null && pairIds.includes(result.point.id)) { 
                                    result.point._delete = true 
                                }
                            }
                        });
                    })
                })
            } else {
                angular.forEach(_this.search.results, function(result) {
                    if (result.point != null) {
                        result.point._delete = false
                    }
                });
            }
        };
    })(this);
    $scope.$watch(watchClient, watchClientAction);

    this.filterSearchResults = function(items) {
        results = $filter('filter')(items, { point: { name: this.search.params.text } })
        results = $filter('orderBy')(results, _this.setOrderBy($("#guided").data("value")))
        results = $filter('unique')(results, 'point.name')
        results = $filter('slice')(results, this.search.meta.startPos, this.search.meta.startPos+this.search.meta.perPage)
        
        _this = this
        angular.forEach(results, function(result, index) {
            points = $filter('filter')(_this.model_labels, { point: { id: result.point.id, name: result.point.name } })

            angular.forEach(points, function(point, index) {
                renderPoint(point, pointsObjects, inactiveColor)
            });
        });
    }

    this.zoomIn = function(event) {
        event.preventDefault();
    
        cameraZ = controls.object.position.z - cameraInterval;
        camera.position.z = cameraZ;
        controls.update();
    };

    this.zoomOut = function() {
        event.preventDefault();
    
        cameraZ = controls.object.position.z + cameraInterval;
        camera.position.z = cameraZ;
        controls.update();
    };

    this.reloadPoints = function(){
        removeSceneObjects(pointsObjects);
        removeSceneObjects(tempObjects);
        removeSceneObjects(highlightedObjects);
        this.filterSearchResults(this.model_labels);
    }

    this.prevPoint = function() {
        if (this.search.meta.currentPage == 1) { return; }
        $("#nextPoint").removeAttr("disabled");
        this.search.meta.currentPage -= 1;
        if (this.search.meta.currentPage == 1) { $("#prevPoint").attr("disabled", "true"); }
        this.search.meta.startPos = (this.search.meta.currentPage - 1) * this.search.meta.perPage;
        this.reloadPoints();
        this.setPaginationText();
    };

    this.nextPoint = function() {
        if (this.search.meta.currentPage == this.search.meta.totalPages) { return }
        this.search.meta.currentPage += 1;
        $("#prevPoint").removeAttr("disabled");
        if (this.search.meta.currentPage == this.search.meta.totalPages || this.search.meta.currentPage * this.search.meta.perPage >= this.search.meta.totalCount) { $("#nextPoint").attr("disabled", "true"); }
        this.search.meta.startPos = (this.search.meta.currentPage - 1) * this.search.meta.perPage;
        this.reloadPoints();
        this.setPaginationText();
    };

    this.deletePair = function(event, model_label_id){
        event.preventDefault();
        
        var confirmDialog = confirm("Are you sure you wish to delete this model label? / ¿Está seguro de que desea eliminar esta etiqueta del modelo?");
        if (confirmDialog == true) {
            ModelLabel.delete( { id: model_label_id }, function(){
                location.reload();
            });
        }
    }

    this.getCoordinates = function(event, model_label_id){
        ModelLabel.get({ id: model_label_id }, function(result) {
            if (result != undefined) {
                renderPoint(result, highlightedObjects)

                if ($("#addPoint").length > 0) {
                    $('#pointX').data('value', result.point_x);
                    $('#pointY').data('value', result.point_y);
                    $('#pointZ').data('value', result.point_z);
                    $("#addPoint").removeAttr("disabled");
                }
            }
        });  
    }

    this.showPoints = function(event, model_label_id){
        var _this = this
        ModelLabel.get({ id: model_label_id }, function(result) {
            if (result != undefined) {
                model_labels = $filter('filter')(_this.model_labels, { point: { id: result.point.id, name: result.point.name } })

                angular.forEach(model_labels, function(object) {
                    renderPoint(object, highlightedObjects, activeColor, model_labels)
                });

                removeSceneObjects(tempObjects);

                if ($("#addPoint").length > 0) {
                    $('#pointX').data('value', 0);
                    $('#pointY').data('value', 0);
                    $('#pointZ').data('value', 0);
                    $("#addPoint").attr("disabled", "true");
                }
            }
        });  
    }

    this.setPaginationText = function() {
        initialCount = this.search.meta.perPage * this.search.meta.currentPage
        if (initialCount >= this.search.results.length) {
            initialCount = this.search.results.length
        }

        filteredResults = $filter('filter')(this.search.results, { point: { name: this.search.params.text } })
        filteredResults = $filter('unique')(filteredResults, 'point.name')
        totalCount = filteredResults.length
        this.search.meta.totalCount = totalCount
        if (this.search.params.text != '' && initialCount >= totalCount) {
            initialCount = totalCount
        }
        this.search.meta.pageStatus = "Displaying " + initialCount + " of " + totalCount + " results. / Mostrando " + initialCount + " de " + totalCount + " resultados.";
    }

    this.addPairToClient = function(pair) {
        if (this.client.scan_session_id == undefined) { return }

        _this = this;
        var confirmDialog = confirm("Are you sure you wish to add this pair? / ¿Está seguro de que desea agregar este par?");
        if (confirmDialog == true) {
            scan_session_pair = new ScanSession.prototype.ScanSessionPair({ scan_session_id: _this.client.scan_session_id, pair_id: pair.id });
            scan_session_pair.$save(function(){
                pair._delete = true
            })
        }
    }

    this.removePairToClient = function(pair) {
        if (this.client.scan_session_id == undefined) { return }

        _this = this;
        var confirmDialog = confirm("Are you sure you wish to remove this pair? / ¿Está seguro de que desea eliminar este par?");
        if (confirmDialog == true) {
            ScanSession.prototype.ScanSessionPair.delete({ scan_session_id: this.client.scan_session_id, id: pair.id }, function() {
                pair._delete = false
            })
        }
    }

    this.getPairName = function(pair) {
        var _this = this
        if (_this.guided_scan) {
            return pair.guidedReferenceNumber + ' - ' + pair.name
        } else {
            return pair.name;
        }
    }

    function renderPoint(object, objectArray, color = activeColor, points = []) {
        // Clear all green highlighted points
        if (color == activeColor) {
            angular.forEach(highlightedObjects, function(object) {
                scene.remove(object) 
            });
        }

        if (points.length > 0) {
            angular.forEach(points, function(object) {
                var dotGeometry = new THREE.Geometry();
                dotGeometry.vertices.push(new THREE.Vector3(object.point_x, object.point_y, object.point_z));
                var dotColor = new THREE.Color(color)
                var dotMaterial = new THREE.PointsMaterial( { size: 6, color: dotColor } );
                var dot = new THREE.Points( dotGeometry, dotMaterial );

                scene.add(dot);
                objectArray.push(dot);
            });
        } else {
            var dotGeometry = new THREE.Geometry();
            dotGeometry.vertices.push(new THREE.Vector3(object.point_x, object.point_y, object.point_z));
            var dotColor = new THREE.Color(color)
            var dotMaterial = new THREE.PointsMaterial( { size: 6, color: dotColor } );
            var dot = new THREE.Points( dotGeometry, dotMaterial );

            scene.add(dot);
            objectArray.push(dot);
        }
    }

    function removeSceneObjects(objects) {
        angular.forEach(objects, function(object) {
            scene.remove(object) 
        });
        objects = []
    }

    if (!Detector.webgl) {
        Detector.addGetWebGLMessage();
    }
    
    function init(font) {
        mouseX = 0;
        mouseY = 0;
    
        windowHalfX = window.innerWidth / 1.5;
        windowHalfY = window.innerHeight / 1.5;

        raycaster = new THREE.Raycaster();
        mouse = new THREE.Vector2();
    
        setTargetModel()
    
        container = document.createElement( 'div' );
        container.style = 'width: 100%;';
        modelContainer.appendChild( container );

        $("#prevPoint").attr("disabled", "true");
        if (parseInt($("#page").data("value")) > 1) {
            $("#prevPoint").removeAttr("disabled");
        }
    
        // camera
        cameraX = 0; 
        cameraY = 0;
        cameraZ = 200;
        cameraInterval = 10;
        camera = new THREE.PerspectiveCamera( 50, window.innerWidth / window.innerHeight, .1, 1000 );
        camera.position.set( cameraX, cameraY, cameraZ );
        camera.lookAt( scene.position );
    
        // adds light to camera
        var pointLight = new THREE.PointLight( 0xffffff, 0.8 );
        camera.add( pointLight );
    
        // adds light to scene
        var ambientLight = new THREE.AmbientLight( 0xcccccc, 0.4 );
        scene.add( ambientLight );
    
        // adds camera to scene
        scene.add( camera );
    
        // loading manager
        var manager = new THREE.LoadingManager();
        manager.onProgress = function ( item, loaded, total ) {
            // console.log( item, loaded, total );
        };
    
        // texture
        var textureLoader = new THREE.TextureLoader( manager );
        var texture = textureLoader.load( modelTexturePath );
    
        var onProgress = function ( xhr ) {
            if ( xhr.lengthComputable ) {
                var percentComplete = xhr.loaded / xhr.total * 100;
                if (percentComplete === 100){
                    if (document.getElementById('toggleModel') != undefined) { 
                        document.getElementById('toggleModel').style = '';
                    }
                    document.getElementById('modelcontainer').style = 'z-index: 10000;';
                }
            }
        };
    
        var onError = function ( xhr ) {
        };
    
        var loader = new THREE.OBJLoader( manager );
        loader.load( modelObjectPath, function ( object ) {
            object.traverse( function ( child ) {
                if ( child instanceof THREE.Mesh ) {
                    child.material.map = texture;
                }
            } );
            object.position.y = - 90;
            object.rotation.y = 135;
            scene.add( object );
            scene.traverse(function(children){
                objects.push(children);
            });
        }, onProgress, onError );

        renderer = new THREE.WebGLRenderer({ antialias: true});
        renderer.setPixelRatio( container.devicePixelRatio );
        renderer.setSize( windowHalfX, windowHalfY );
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
    
        // load the background texture
        backgroundTexture = THREE.ImageUtils.loadTexture( '/images/scan-bg-left.jpg' );
        backgroundMesh = new THREE.Mesh(
            new THREE.PlaneGeometry(2, 2, 0),
            new THREE.MeshBasicMaterial({
                map: backgroundTexture
            }));
        backgroundMesh.material.depthTest = false;
        backgroundMesh.material.depthWrite = false;
    
        // create the background scene
        backgroundScene = new THREE.Scene();
        backgroundCamera = new THREE.Camera();
        backgroundScene.add(backgroundCamera);
        backgroundScene.add(backgroundMesh);
    
        container.appendChild( renderer.domElement );
        // container.addEventListener("mousemove", onDocumentMouseMove, false );
        // container.addEventListener("touchmove", onDocumentTouchMove, false);
        container.addEventListener( 'mousedown', onDocumentMouseDown, false );
        container.addEventListener( 'touchstart', onDocumentTouchStart, false );
        
        // add controls
        controls = new THREE.OrbitControls( camera, renderer.domElement );
        controls.enablePan = true
        controls.enableRotate = true
    
        // events
        window.addEventListener( 'resize', onWindowResize, false );
    }
    
    function setTargetModel() {
        modelContainer = document.getElementById('modelcontainer');

        if ($("#modelTarget").data("value") != undefined) {
            modelTarget = $("#modelTarget").data("value")
        }

        if (modelTarget == 'male') {
            modelObjectPath = '/assets/3d_models/male/Male.obj';
            modelTexturePath = '/assets/3d_models/male/male.jpg';
        } else {
            modelObjectPath = '/assets/3d_models/female/Female.obj';
            modelTexturePath = '/assets/3d_models/female/female.jpg';
        }
    }
    
    function addLabel( name, location, font ) {
        var textGeo = new THREE.TextBufferGeometry( name, {
            font: font,
            size: 6,
            height: 1,
            curveSegments: 1
        });
        var textMaterial = new THREE.MeshBasicMaterial( { color: 0xffffff } );
        var textMesh = new THREE.Mesh( textGeo, textMaterial );
        textMesh.position.copy( location );
        scene.add( textMesh );
    }
    
    function addLine(geometry, material) {
        scene.add( new THREE.Line( geometry, material ) );
    }
    
    function onWindowResize() {
        renderer.setSize( window.innerWidth / 1.5, window.innerHeight / 1.5);
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
    }
    
    function onDocumentMouseMove( event ) {
        mouseX = ( event.clientX - windowHalfX ) / 2;
        mouseY = ( event.clientY - windowHalfY ) / 2;
    }
    
    function onDocumentTouchMove( event ) {
        mouseX = ( event.clientX - windowHalfX ) / 2;
        mouseY = ( event.clientY - windowHalfY ) / 2;
    }

    function onDocumentTouchStart( event ) {

        event.preventDefault();

        event.clientX = event.touches[0].clientX;
        event.clientY = event.touches[0].clientY;
        onDocumentMouseDown( event );

        $('#posX').text("X:" + mouse.x);
        $('#posY').text("Y:" + mouse.y);
    }

    function onDocumentMouseDown( event ) {

        event.preventDefault();

        if (($("#userIsAdmin").data("value") != 'administrator') || 
        $("#clientId").data("value") != undefined ) { return }

        mouse.x = ( event.clientX / renderer.domElement.width ) * 2 - 0.75;
        mouse.y = - ( event.clientY / renderer.domElement.height ) * 2 + 1.45;

        raycaster.setFromCamera( mouse, camera );

        var intersects = raycaster.intersectObjects( objects );

        if ( intersects.length > 0 ) {
            removeSceneObjects(highlightedObjects);

            $("#addPoint").removeAttr("disabled");

            $('#pointX').text("Point X: " + intersects[ 0 ].point.x);
            $('#pointX').data('value', intersects[ 0 ].point.x)
            $('#pointY').text("Point Y: " + intersects[ 0 ].point.y);
            $('#pointY').data('value', intersects[ 0 ].point.y)
            $('#pointZ').text("Point Z: " + intersects[ 0 ].point.z);
            $('#pointZ').data('value', intersects[ 0 ].point.z)

            var dotGeometry = new THREE.Geometry();
            dotGeometry.vertices.push(intersects[ 0 ].point);
            var dotColor = new THREE.Color(activeColor)
            var dotMaterial = new THREE.PointsMaterial( { size: 6, color: dotColor } );
            var dot = new THREE.Points( dotGeometry, dotMaterial );

            if (tempObjects.length > 0) {
                scene.remove(tempObjects[tempObjects.length - 1]) 
            }
            
            scene.add(dot);
            tempObjects.push(dot)
        }

    }
    
    function animate() {
        requestAnimationFrame( animate );
        render();
        update();
        renderer.render( scene, camera );
    }
    
    function update() {	
        controls.update();
    }
    
    function render() {
        renderer.autoClear = false;
        renderer.clear();
        renderer.render( backgroundScene, backgroundCamera );
    }

    $(window).resize(function(){
        SCREEN_WIDTH = window.innerWidth;
        SCREEN_HEIGHT = window.innerHeight;
        camera.aspect = SCREEN_WIDTH / SCREEN_HEIGHT;
        camera.updateProjectionMatrix();
        renderer.setSize( SCREEN_WIDTH, SCREEN_HEIGHT );
    });
}
ModelLabelsCtrl.$inject = ['$scope', '$filter', 'ModelLabel', 'ClientPair', 'Client', 'ScanSession', 'User'];

angular.module('AnewApp').controller('ModelLabelsCtrl', ModelLabelsCtrl);
