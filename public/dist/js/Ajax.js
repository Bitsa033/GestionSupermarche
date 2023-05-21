function calculer_difference(url1) {
    $(document).ready(function () {
        $("#btn_calculer").click(function (e) {
            e.preventDefault()
            var somme = $('#somme').val()
            var depenses = $('#depenses').val()
            
            $.ajax({
                url: url1,
                method: "POST",
                data: { somme:somme,depenses:depenses},
                success: function (data) {
                    console.log(data.resultat);
                    // $('#quickForm')[0].reset()
                    // $('.table').load(location.href+' .table-bordered')
                    $('#resultat').html('Reste: '+data.resultat+' FCFA')
                    // setInterval(() => {
                    //     location.href="stock_sortie"
                    // }, 3000);
                    
                    //swal("Good job!", data.message, data.icon)
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(textStatus)
                }
    
            })
    
        })
    
    })
}