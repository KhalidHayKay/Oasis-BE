<script>
    window.opener.postMessage({
            success: true
        },
        "{{ env('FRONTEND_URL') }}"
    );

    window.close();
</script>
