

    <script>
        document.addEventListener('livewire:initialized', () => {
        @this.on('gotoitem', (event) => {
            postid = (event.test);


            if (postid == 'kst') {

                $("#kst").focus();
                $("#kst").select();
            }
            if (postid == 'ksm_date') {

                $("#ksm_date").focus();
                $("#ksm_date").select();
            }
            if (postid == 'ksm') {

                $("#ksm").focus();
                $("#ksm").select();
            }
            if (postid == 'kst') {

                $("#kst").focus();
                $("#kst").select();
            }
            if (postid == 'kst_count') {

                $("#kst_count").focus();
                $("#kst_count").select();

            }
            if (postid == 'main_id') {

                $("#main_id").focus();
                $("#main_id").select();

            }
            if (postid == 'acc') {

                $("#acc").focus();
                $("#acc").select();

            }

            if (postid == 'notes') {
                $("#notes").focus();
                $("#notes").select();
            }
            if (postid == 'sul_begin') {

                $("#sul_begin").focus();
                $("#sul_begin").select();
            }

            if (postid == 'bank_id') {
                $("#bank_id").focus();
                $("#bank_id").select();
            }
            if (postid == 'price_type_id') {
                $("#price_type_id").focus();
                $("#price_type_id").select();
            }
            if (postid == 'pay') {
                $("#pay").focus();
                $("#pay").select();
            }
            if (postid == 'barcode_id') {
                $("#barcode_id").focus();
                $("#barcode_id").select();
            }if (postid == 'item_id') {
                $("#item_id").focus();
                $("#item_id").select();

            }if (postid == 'q1') {
                $("#q1").focus();
                $("#q1").select();
            }
            if (postid == 'q2') {
                $("#q2").focus();
                $("#q2").select();
            }
            if (postid == 'price_input') {
                $("#price_input").focus();
                $("#price_input").select();
            }
            if (postid == 'notes') {
                $("#notes").focus();
                $("#notes").select();
            }

        });
        });
    </script>


