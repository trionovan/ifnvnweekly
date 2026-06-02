fetch("http://localhost/")
  .then(response => response.text())
  .then(data => {
    console.log(data);
  });