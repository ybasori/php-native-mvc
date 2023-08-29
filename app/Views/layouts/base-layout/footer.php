<script>
    var backdropmodal = document.createElement("div");
    backdropmodal.classList.add("modal-backdrop")
    backdropmodal.classList.add("fade")

    function openModal(id) {
        var el = document.getElementById(id);
        el.style.display = 'block';
        el.classList.add('in');
        var body = document.body;
        body.classList.add('modal-open');
        body.style['padding-right'] = '17px'

        body.appendChild(backdropmodal);
        backdropmodal.classList.add("in")

        return new Promise((resolve) => {
            resolve();
        })
    }

    function closeModal(id) {
        var el = document.getElementById(id);
        el.style.display = 'none';
        el.classList.remove('in');
        var body = document.body;
        body.classList.remove('modal-open');
        body.style = ''
        backdropmodal.remove();
    }

    function setAuth(value) {
        localStorage.setItem("auth", JSON.stringify(value))
    }

    function getAuth() {
        if (localStorage.getItem("auth")) {
            return JSON.parse(localStorage.getItem("auth"))
        }
        return null
    }

    function removeAuth() {
        localStorage.removeItem("auth")
        window.location.reload()
    }

    if (!getAuth()) {
        if (window.location.pathname + window.location.search !== "/admin?page=login") {
            window.location.href = "/admin?page=login"
        }
    } else {
        if (window.location.pathname + window.location.search === "/admin?page=login") {
            window.location.href = "/admin"
        }
    }
</script>

</body>

</html>