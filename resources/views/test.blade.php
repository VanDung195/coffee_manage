<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    .modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fefefe;
    padding: 20px;
}

.close {
    position: absolute;
    top: 0;
    right: 0;
    font-size: 20px;
    font-weight: bold;
    cursor: pointer;
}
#abc {
    background-color: red;
    color: black;
    border: 12px solid red;
}
</style>
<body id="body">
    {{-- <button id="show" onclick="show()">
        X
    </button>
    <a>
        <i class="material-icons">
        </i>
    </a>
    <div id="create" style="display: none;">
        <a href="">Create</a>
    </div>
    <div id="div-show-create">
        <form id="show-create">
            <input type="text" name="" id="">
            <input type="text" name="" id="">
        </form>
    </div> --}}
    <form action="{{route('test2')}}">
        <input type="text" class="form-control" name="test[]">
        <input type="text" class="form-control" name="test[]">
        <input type="text" class="form-control" name="test[]">
        <input type="text" class="form-control" name="test[]">
        <button type="submit">submit</button>
    </form>

    <button id="show" onclick="Show()">
        X
    </button>
    <div id="create" style="display: none;">
        <button id="ShowCreate" onclick="ShowCreate()">Create</button>

        <div id="show-create" style="display:none;">
            <form class="container-fluid" action="main.php" method="post" enctype="multipart/form-data">
                <label for="image">Chọn hình ảnh:</label>
                <input type="file" name="image">
                <input type="text" name="artist">
                <input type="submit" name="submit" value="Tải lên">
            </form>
            <li>Create</li>
            <li>Create</li>
        </div> 
    </div>

    <button id="createe">Try it</button>
    <button id="myBtn">Try it</button>

    <p id="demo">





    <button id="openModalBtn">Open Modal</button>
    <div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModalBtn">&times;</span>
        <div id="dynamicContent"></div>
    </div>
    </div>

</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Get modal and buttons
    var modal = document.getElementById('modal');
    var openModalBtn = document.getElementById('openModalBtn');
    var closeModalBtn = document.getElementById('closeModalBtn');

    // Add event listener to open modal button
    openModalBtn.addEventListener('click', function () {
        // Clear previous content
        document.getElementById('dynamicContent').innerHTML = '';

        // Create new content (in this case, a div containing an input field)
        var dynamicContent = document.createElement('div');
        dynamicContent.innerHTML = `<input type="text" placeholder="Enter something" id="abc">
                                    `;

        // Append the new content to the modal
        document.getElementById('dynamicContent').appendChild(dynamicContent);

        // Display the modal
        modal.style.display = 'block';
    });

    // Add event listener to close modal button
    closeModalBtn.addEventListener('click', function () {
        // Hide the modal
        modal.style.display = 'none';
    });

    // Close modal if the overlay is clicked
    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});




    function Show() {
        document.getElementById('create').style.display = "block";
    }
    function ShowCreate() {
        document.getElementById('show-create').style.display = "block";
    }
    // function show() { 
    //     let btn = document.getElementById('show');
    //     create.style.display = "block";
    // }

    const element = document.getElementById("myBtn");
    element.addEventListener("click", myFunction);

    function myFunction() {
        console.log(1);
    document.getElementById("demo").innerHTML = "Hello World";
    }

    const create = document.getElementById("createe");
    let clicked = false;
    createe.addEventListener("click", functiont);
    function functiont() { 
        if(clicked==false)
        {
            clicked=true;
            console.log(clicked);
        }
        else
        {
            clicked=false;
            console.log(clicked);
        }
     }

    document.addEventListener("click",function(){
        if(clicked)
        {
            document.getElementById('createe').style.display="none";
            clicked=false;
        }else
        {
            clicked=true;
        }
    })
    // create.addEventListener("click", function() {
    //     console.log(1);
    //     is_active = true;
    //     clicked = true;
    // });

    // if(is_active==true)
    // {
    //     document.addEventListener("click", function() {
    //     if (clicked) {
    //         document.addEventListener("click", choise());
    //         clicked = false;
    //     } else {
    //         document.removeEventListener("click", choise());
    //         clicked=true;
    //     }
    // })
    // }
    
    
    // function choise() {
    //     if (clicked) {
    //         document.getElementById('create').style.display = "none";
    //         alert("Bạn đã click vào màn hình");
    //     } else {
    //         alert("Bạn đã click vào màn hình cc");
    //     }
    // }


</script>
</html>