let countryList;

$(document).ready(function(e){

    $.get('http://localhost:8080/flights/api?option=country',function(data) {
        countryList = JSON.parse(data);
        $.each(JSON.parse(data), (i,j) =>{
            $.each(j, (ii,jj) => {
                $('select[name=country]').append('<option value='+ii+'>'+jj+'</option>')
            })
        })
    });

    let div=document.querySelector('.country-input');
    div.addEventListener('change',function(e){
    if($(this).val()) {
        $('.error').empty();
    }
})

    let data = [
            {"text" : "Feature Text 1", "color": "rgb(116, 45, 45)"},
            {"text" : "Feature Text 2", "color" : "rgb(197, 153, 153)"},
            {"text" : "Feature Text 3", "color": "rgb(117, 185, 62)"}
    ];

    let i=0;
    setInterval(function() {
            if(i == data.length)
            {
                i=0;
            }
            let text=`<p style=color:${data[i].color}>${data[i].text}</p>`;
            i++;
            $(".features").html(text);
            }, 3000);
        });

    $(document).on('change','select[name="lookup"]', function(e){
            var option = e.target.value;
            var country = $('select[name="country"]').val();
            if(country == "") {
                $("select[name='country']").css({border:"1px solid rgb(255,0,0)"}).after('<p class="error" style="color:red">Please select the country</p>');
                $("select[name='lookup']").val("");
            } else if(option == "") {
                $("select[name='lookup']").css({border:"1px solid rgb(255,0,0)"}).after('<p class="error" style="color:red">Please select the option</p>');
            }  else {
                    $.get('http://localhost:8080/flights/api?option='+option,function(data) {
                    let obj = JSON.parse(data);
                    $("select[name=lookup]").next(".result").empty();
                        $.each(obj, (j,i)=>{
                            if(typeof(i) == "object") {
                                $.each(i, (ii,jj) => {
                                    if(country == ii) {
                                        $.each(jj, (k,v) => {
                                            $("select[name=lookup]").next(".result")
                                            .append(`<p>${v.name} is located near ${v.landmark}</p> Number of flights:(${v.flights.length})`);
                                        });
                                    }
                                })
                            } else {
                                $("select[name=lookup]").next(".result").append("<p>"+i+"</p>");
                            }
                        })
                    });
                }
            });

            function checkAvailability(e) {
                let flightId = e.getAttribute('id');
                $.get('http://localhost:8080/flights/api/check/'+flightId,function(result,status){
                let obj=JSON.parse(result);
                var text='';
                $.each(obj, (i,j)=>{
                    text+='<p>'+j+'</p>';
                    })
                $('.check-btn').after(text);
                })
            }

            function getFlights(val) {
                $.ajax({
                    url: 'http://localhost:8080/flights/api/all',
                    type: 'POST',
                    data: { "text" : val},
                    headers : { "apiKey" : "key"},
                    success: function(res) {
                        let obj1=["flight_id", "available"];
                        let obj = JSON.parse(res);
                        if(Object.keys(obj.data).length && obj.status == 200) {
                            let table='<table border="1">';
                            let id;
                                $.each(obj.data, (i,j)=>{
                                    table+='<tr>';
                                    $.each(j, (ii,jj)=> {
                                        if(ii == "flight_id") {
                                            id = jj;
                                        }
                                        if( ii != "flight_id" && ii != "available") {
                                            table+=`<td>${jj}</td>`;
                                        }
                                    });
                                    table+='<td><button class="btn btn-primary check-btn" id='+id+' onClick="checkAvailability(this)">Check availability</button></td></tr>';
                                });
                                table+='</table>';
                                $('.results').html(table);
                            } else if(Object.keys(obj.data).length == 0) {
                                $('.results').html("Sorry, No search results for the input");
                            } else if(obj.status == 401) {
                                $('.results').html('Unauthorized to access the api. Please contact the admin');
                            } else {
                                $('.results').html('No Flights');
                            }
                    },
                    error: function(e) {
                        console.log(e);
                    }
                })
            }
