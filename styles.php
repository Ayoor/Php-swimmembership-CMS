<?
echo "
<style>

</style>



.sidebar{
    background-color: #2A2D3E;
      border-top: none;
      width: 17%;
      height: 100vh; 
      display: flex;
      flex-direction: column;
      position: fixed;
      z-index: 5;
      top:0
   }
   .page{ 
      height: 100vh;
      width: 100%;
   
   }
   #userimg{
      
      width: 25%;
      margin-top: 30px;
   }
   
   body{
      padding: 0;
      margin: 0;
      background-color: #212332;
      color: white;
   }
   
   .sidebarlogo{
      display: flex;
      justify-content: flex-start;
      padding-left: 20px;
      margin-top: 50px;
   }
   .sidebaroptions ul{
      list-style-type: none;
      padding: 0px;
      width: 100%;
      margin: 0;
   } 
   .sidebaroptions li{
      padding: 10px 0 10px 25px;
      width: 100%;
   }
   
   .sidebaroptions li:hover{
   background-color:#393d55 ;
   
   }
   
   
   .sidebar a{
      text-decoration: none;
      color: white;
   }
   
 .sidebaroptions ul li.active {
 background-color: hotpink !important;
   font-weight: bold;
 }
   .sidebaroptions{
      margin-top: 20%;
      width: 100%;
   }
   
   .nav-options{
      background: transparent;
      height: 7vh;
      z-index: 1;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      
     
      }
   
   .nav-options a{
      margin-right: 55px;
   }
   
   .nav-options a:hover{
     
      color: hotpink;
   }
   
   
   nav a{
      text-decoration: none;
      color:white;
   }
   
   .panel{
      width: 79%;
      height: 92vh;
      border: 1px solid whitesmoke;
      border-radius: 35px;
      margin-left: 20%;
      padding: 30px;
   }
   .nav-link{
   color: white ;
   
    }
    .nav-link:hover{
      color: white;
      background-color: #393d55;
    }
    .active{
      color: #fc8070 !important;
      background-color: transparent !important;
    }
   
    .details{
      width: 100%;
      height: 83%;
      border: 1px solid whitesmoke;
      display: flex;
      padding: 1.5%;
    }
   
    .overview{
      /* border: 1px solid red; */
      width: 35%;
      height: 90%;
      margin-right: 100px;
      padding: 30px;
      overflow: scroll;
      overflow-x: hidden;
      
    }
    .detailed{
      border: 1px solid green;
      width: 60%;
      height: 90%;
    }
   
    .overview-card{
      margin-top: 20px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      background-color: white;
      
    }
  
   .desc-div{
      width: 90%;
      padding-left: 20px;
      padding-right: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
   }
   
   .img-div{
      height: 100%;
      width: 40%;
   }
    .overview-card img{
      width: 100%;
      height: 100%;
    }
   
    .overview a{
      text-decoration: none;
    }
    
    .modal{
       color: black;
    }
    
    /* tables */
    table {
       width: 100%;
       border-collapse: collapse;
   }
   
   th, td {
       text-align: center;
       padding: 10px;
   }
   
   thead {
       background-color: #212332;
       color: white;
   }
   
   tbody tr:nth-child(even) {
       background-color: #393d55;
       color: white;
   }
   
   #deletebtn {
       color: white;
   }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
";

?>