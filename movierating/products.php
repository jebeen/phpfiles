<script>

function removeComment(e) {
    var btn=e.closest('.reviews').getAttribute('id');
    let id=$('#'+btn).find('.id').val();
    let form='<form id="deleteForm" method="post">';
    form += '<input type="hidden" name="delete" value=1><input type="hidden" name="id" value='+id+'><br>';
    form+='</form>';
    $(".delete").closest('#'+btn).find('.deletediv').html(form);
    $('#deleteForm').submit();

    $('#'+btn).hide('slow', $('#'+btn).remove());
}

function editComment(e) {
    let mid=e.getAttribute('data-mid');
    var btn=e.closest('.reviews').getAttribute('id');
    let uid=$('#'+btn).find('.uid').val();
    let id=$('#'+btn).find('.id').val();
    let comment=$('#'+btn).find('.comment').text();

    let form='<form name="editForm" method="post">';
    form += '<input type="hidden" name="edit" value=1><input type="hidden" name="id" value='+id+'><br>';
    form+='<input type="hidden" name="mid" value='+mid+'><br><input type="hidden" name="uid" value='+uid+'>';
    form+='<input type="text" name="comment" size="50" class="comment" value='+comment+'>';
    form+='<input type="submit">';

    form+='</form>';

    $(".edit").closest('#'+btn).find('.editdiv').html(form);
}

</script>

<?php
class products {

    public function displayMovies($movies,$id='') {
        $html = '';
        $excludekeys = array("mid"=>1,"name"=>2, "email"=>3);
        if($id) {
            $movies=array_filter($movies, function($movie) {
                if($movie['mid'] == $id){
                    return $movie;
                }

            });
        }
        foreach ($movies as $key => $value) {

            $html.= '<div class="products-section" style="float:left;margin: 10px; width: 300px;border:1px solid green; background-color: #ccc; padding:10px;">';
            foreach ($movies[$key] as $k => $v) {
                $path='./images/'.$v;
                if($k == 'mid') {
                    $mid=$v;
                }

                $sppLink='./'.$mid;
                $html.='<a href='.$sppLink.' style="text-decoration: none;color: black">';
                if(!array_key_exists($k, $excludekeys)) {
                    if($k == 'thumbnail') {
                        $html .= '<img src='.$path.' width=300 heigth=200 >';
                    } else {

                            $html .= '<p>'.$k." : ".$v.'</p>';
                    }
                }
                $html.='<a>';
            }
            $html.="</div>";
        }
        echo $html;

    }

    public function displayReviews($reviews, $mid) {
        $html='<h3>Comments</h3>';
        foreach ($reviews as $key => $value) {
            $html .= '<div class="reviews" id=reviews'.$key.' style="float: left; margin-left: 10px; margin-top: 10px; width: 400px;border:1px solid #a32; padding: 10px; background-color: #ccc;">';
            foreach ($value as $k => $v) {
                if($k == 'uid' || $k == 'id') {
                    $html.='<input type="hidden" class='.$k.' value='.$v.'>';
                } else {
                    $html.='<p class='.$k.'>'.$v.'</p>';
                }

            }
            $html.= '<div class="editdiv"></div><div class="deletediv"></div>';
            $html .= '
            <button onClick="editComment(this)" class="edit" data-mid='.$mid.' style="position: relative; top: -150px; right: -300px">Edit</button>

            <button onClick="removeComment(this)" class="delete" data-mid='.$mid.' style="position: relative; right: -300px; top: -150px">Remove</button>
            </div>';
        }
        echo $html;
    }
}

?>
