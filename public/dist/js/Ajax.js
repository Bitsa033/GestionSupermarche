function setData(url1) {
    $(document).ready(function () {
        $("#btn_valider_achat").click(function (e) {
            e.preventDefault()
            var quantite = $('.quantite').val()
            var id_commande = $('#id_commande').val()
            var mag_id = $('.mag_id').val()
            var mag_name = $('.mag_name').val()
            var btn_valider_achat=$('#btn_valider_achat').val()
            console.log('Quantité: '+quantite);
            console.log('Commande: '+id_commande);
            console.log('magasin: '+mag_name);
            // var depenses = $('#depenses').val()
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
                data: { quantite:quantite,id_commande:id_commande,
                    btn_valider_achat:btn_valider_achat,mag_id:mag_id,
                    mag_name:mag_name
                },
                success: function (data) {
                    console.log(data.resultat);
                    // $('#quickForm')[0].reset()
                    // $('.table').load(location.href+' .table-bordered')
                    // $('#resultat').html('Reste: '+data.resultat+' FCFA')
                    // setInterval(() => {
                    //     location.href="stock_sortie"
                    // }, 3000);
                    
                    //swal("Good job!", data.message, data.icon)
                },
                error: function (textStatus) {
                    console.log(textStatus)
                }
    
            })
    
        })
    
    })
}