<!DOCTYPE html>
<html lang="en">

<body>

    @if(auth()->user()->role == 1 || auth()->user()->role == 2)
    <style type="text/css">
        .add_new{
            position: absolute;
            cursor: pointer;
            width: 50px;
            height: 50px;
            -webkit-filter: invert(100%);
            filter: invert(100%);
        }

        .add_new:active{
            transform: scale(0.9);
        }
    </style>

    <script type="text/javascript">
        function rotate(){
            let tuerquita = document.querySelector(".add_new");
            
            tuerquita.animate([{transform: 'rotate(360deg)'}], {duration: 1000});
        }
    </script>

    <img src="tuerquita.png" class="add_new" onclick="rotate()">    
    @else
    
    @endif
    
</body>
</html>