function calculer_difference(url1) {
    $(document).ready(function () {
        $("#btn_calculer").click(function (e) {
            e.preventDefault()
            var somme = $('#somme').val()
            var depenses = $('#depenses').val()
            // const euro = new Intl.NumberFormat('fr-FR', {
            //     style: 'currency',
            //     currency: 'EUR',
            //     minimumFractionDigits: 2
            //   });
              
            //   console.log(euro.format(8000)); 
            //   console.log(euro.format(25));
            //   console.log(euro.format(99600023147));
            
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