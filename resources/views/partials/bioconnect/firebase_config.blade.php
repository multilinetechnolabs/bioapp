<script src="{{'https://www.gstatic.com/firebasejs/'.env('FIREBASE_VERSION').'/firebase.js'}}"></script>

<script>
    var firebaseConfig = {
        apiKey: "{{ env('FIREBASE_API_KEY') }}",
        authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
        databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}",
        projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
        storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
        messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}"
    };

    // Initialize firebase
    var firebaseApp = firebase.initializeApp(firebaseConfig);
    
    // Initialize the firebase client
    function initthefb(childs) {
        return firebaseApp.database().ref().child(childs);
    }
</script>

@if (!empty(Auth::user()))
    @include('partials.bioconnect.notifications')
@endif
