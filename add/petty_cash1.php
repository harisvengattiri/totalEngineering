<input type="text" name="total_days" class = "amount" value="" />
<input type="text" name="cost_per_day" class = "amount" value="" />

<p>Total Cost: $<span id="total_cost"></span></p>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$('.amount').keyup(function () {
    var sum = 0;
    $('.amount').each(function() {
        sum += Number($(this).val());
    });
    $('#total_cost').html(sum);
});
</script>