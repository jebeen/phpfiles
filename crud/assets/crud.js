function removeRow(index) {
  let ele=$(`button[name=remove_${index}]`).closest('tr').find('.data').eq(0).text();
  $.ajax({
    url: "http://localhost:8080/restapi/crud/removeUser",
    type: 'POST',
    data: {id: ele},
    headers: {
      'key': 'KEYVALUE'
    },
    success: function(result) {
      if(result.status) {
        $(".statusMsg").text("Success");
        setTimeout(()=>{
            $(`button[name=remove_${index}]`).closest('tr').css({"background-color":"red"}).fadeOut();
          }, 2000);
        } else {
          $(".errMsg").text("Error");
        }
      },
   error: function(e) {
    console.log(e);
   }
 })
}

function saveRow(index){
  let data={};
  let rows = $(`button[name=save_${index}]`).closest('tr').find('.data');
  $.each(rows,(i,j) => {
    data[i]=$(j).text();
  })
  $.post('http://localhost:8080/restapi/crud/updateUser',{
    data: data,
    headers: {'key' :'KEYVALUE'}
  }, function(result,status) {
      if(result.status) {
        $(".statusMsg").text("Data is been updated successfully");
        $(`button[name=save_${index}]`).css({"display":"none"});
        $(`button[name=save_${index}]`).prev(`button[name=edit_${index}]`).css({"display":"block"});
        window.location.reload();
      } else {
        $(".errMsg").text('Error in updating');
      }
  })
}

function makeEditable(index) {
  const el = document.getElementsByName(`edit_${index}`);
  $(el).closest('tr').attr('contenteditable',true);
  $(el).closest('tr').find(`button[name=save_${index}]`).css({"display":"block"})
  $(el).css({"display":"none"});
}

async function getUsers() {
  $.get('http://localhost:8080/restapi/crud/getUsers',  function(data,res) {
    let index=0;
    if(data.status) {
      let table="<table border='2'><tr><th>ID</th><th>Name</th><th>Email</th><th>City</th><th>Country</th><th>Action</th></tr>";
      data.data.map((users) => {
        table += `<tr class=row_${index}>`;
        $.each(users, (i,j) => {
          table += "<td class='data'>"+j+"</td>";
        })
        table+=`<td class=button_row_${index}><button name=edit_${index} onClick=makeEditable(${index})>Edit</button><button name=save_${index}
        style=display:none onClick=saveRow(${index})>Save</button><button name=remove_${index} onClick=removeRow(${index})>Remove</button>`;
        table+="</td></tr>";
        index++;
      })
      table+="</table>";
      $(".display").html(table);
    }
  })
}

function getCity() {
  $.getJSON('./city.json', function(data,status) {
    var country=[];
    $.each(data.city, (i,j) => {
      let obj={};
      obj['id']=j.id;
      obj['country']=j.country;
      country.push(obj);
      $("select[name='city']").append(`<option id=${j.id}>${j.title}</option>`);
    })
    $("input[name='country']").val(country[0].country);
    $("select[name='city']").on('change', function() {
      let optCountry=country.filter(c => c.id == $(this).children(":selected").attr("id"));
      $("input[name='country']").val(optCountry[0].country);
    })
  })
}

function getData() {
  $.getJSON('./city.json', function(result) {
    $.each(result.city, (i,j) => {
      $(".result").append(`<p class=${j.id}>${j.title}</p>`);
    })
  })

  fetch('http://localhost:8080/restapi/crud/getAllUsers',{
    'body': JSON.stringify({"name": "value", "id": 1}),
    'headers': {"key": "KEYVALUE"},
    'method': "POST"
  })
  .then(res=>res.json())
  .then(data=> {
    console.log(data);
  })
  .catch(e => $(".error").html("error in processing"))
}
