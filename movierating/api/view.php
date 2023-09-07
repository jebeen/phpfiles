<html>
<head>
    <title>REST API</title>
    <script type="text/javascript" src="../../bootstrap/js/jquery.min.js"></script>
    <script type="text/javascript">
        function getText(data) {
            $.ajax({
                url: './process.php',
                type: "GET",
                data: "text="+data,
                contentType: 'application/json',
                success: function(res) {
                    $("table").html(res);
                },
                error: function(err) {
                    console.log(err);
                }
            })
        }
    </script>
</head>
<body>

<?php
class View {
    private $userdata = [];
    private $users = 0;

    public function setData() {
        $this->userdata = file_get_contents("./users.json");
    }

    public function getData() {
        return $this->userdata;
    }

    public function getCount() {
        if(!empty($this->userdata)) {
            $this->users = count(json_decode($this->userdata));
        }
        return $this->users;
    }

    public function addUser($user) {
        $temp=json_decode($this->userdata);
        array_push($temp, $user);
        $json=json_encode($temp);
        file_put_contents('./users.json', $json);
        $this->setData();
    }

    public function editUser($name, $id, $age) {
        $temp=json_decode($this->userdata);
        foreach ($temp as $key => $value) {
            if($value->id == $id) {
                $temp[$key]->name=$name;
                $temp[$key]->id=$id;
                $temp[$key]->age=$age;
                break;
            }
        }
        $json=json_encode($temp);
        file_put_contents('./users.json', $json);
        $this->setData();
    }

    public function deleteUser($id) {
        $temp=json_decode($this->userdata);
        foreach ($temp as $key => $value) {
            if($value->id == $id) {
                unset($temp[$key]);
                break;
            }
        }
        $json=json_encode($temp); // array to json
        file_put_contents('./users.json', $json);
        $this->setData();
    }
}

class Create {
    private $name, $age;

    public function getDetails() {
        ?>
        <h3>Add user</h3>
    <form name="createUser" method="post" action="./process.php">
        <input type="text" name="name" placeholder="Enter your Name" value="" />
        <input type="text" name="age" placeholder="Enter your Age" value="" />
        <input type="submit">
    </form>
    <h3>Search User</h3>
        <input type="text" name="name" onChange="getText(this.value)"/>
        <?php
    }
}

function display($id='', $data=[]) {

    $obj1=new View();
    $obj1->setData();
    if(empty($data)) {
        $data = json_decode($obj1->getData());
    } else {
        $data = $data;
    }

    if($id) {
        $data = array_filter($data,function($item) {
            if($item->id == $_GET['id']) {
                return $item;
            }
        });
    }
    ?>
    <table border="2" width="50%">
            <tr>
                <th>Name</th>
                <th>Id</th>
                <th>Age</th>
                <th>Action</th>
            </tr>
            <?php
    if(!empty($data)) {
        $editable = "";
        if(isset($_GET['id']))
        {
            $editable="contenteditable";
        }
        foreach ($data as $key => $value) {
                ?>
            <tr>
                <?php
                if(isset($_GET['id'])) {
                    if(isset($_GET['edit'])) {
                        $option="edit";
                    } else {
                        $option="delete";
                    }
                    ?>
                    <form method="post" action="http://localhost:8080/movierating/api/process">
                      <input type="hidden" name=<?php echo $option?> value="1">
                    <?php
                }
                foreach ($data[$key] as $k => $v) {
                  if($k == "id") {
                      $id = $v;
                    }
                    ?>
                    <td <?php echo $editable ?> >
                      <input type="text" name=<?php echo $k?> value=<?php echo $v ?> />
                    </td>
                    <?php
                  }
                  if(!isset($_GET['id'])) {
                    ?>
                    <td>
                      <a href="edit/<?php echo $id?>">Edit</a>
                      <a href="delete/<?php echo $id?>">Delete</a>
                    </td>
                    <?php
                  } else {
                    ?>
                    <td><button type="submit">Ok</button></td>
                    <?php
                  }
                  if(isset($_GET['id'])) {
                    ?>
                  </form>
                  <?php
                }
                ?>
              </tr>
              <?php
            }
          }
          else {
            ?>
            <tr><td colspan="4">No records</td></tr>
            <?php
          }
          ?>
        </table>
    <?php
}

if($_SERVER['REQUEST_URI'] == '/movierating/api/view') {
   display();
   $obj=new Create();
    $obj->getDetails();
}
?>

</body>
</html>
