<div>
        <div>

            <form method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="personnage" class="col-sm-4 col-form-label text-light">Personnage</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="personnage" id="personnage" placeholder="Nom du personnage">
                    </div>
                </div>
            
                <div class="row mb-3">
                    <label for="datelive" class="col-sm-4 col-form-label text-light">Date du live</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="datelive" id="datelive" placeholder="jj-mm-yyyy">
                    </div>
                </div>

                <textarea name="resume" id="content-form" class="d-none"></textarea>


            <div class="row row-editor">
                <div class="editor">
                    <p>Entrer l'article</p>
                </div>
            </div>
            <button  type="button" id="refreshPreview" class="btn btn-success">Aperçu</button>
                <button type="submit" class="btn btn-success">Envoyer</button>
            </form>
        </div>
        <div class="centered">
            <h2>Aperçu</h2>
            <div class="ck ck-content bg-light border" id="preview">

            </div>
        </div>
    </div>
    </div>
    <script src="./ckeditor5/build/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>

        ClassicEditor.create(document.querySelector('.editor'), {

            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'outdent',
                    'indent',
                    '|',
                    'imageUpload',
                    'blockQuote',
                    'insertTable',
                    'mediaEmbed',
                    'undo',
                    'redo'
                ]
            },
            language: 'fr',

            image: {
                styles: [
                    'alignLeft', 'alignCenter', 'alignRight'
                ],
                resizeOptions: [
                    {
                        name: 'resizeImage:original',
                        label: 'Original',
                        value: null
                    },
                    {
                        name: 'resizeImage:50',
                        label: '50%',
                        value: '50'
                    },
                    {
                        name: 'resizeImage:75',
                        label: '75%',
                        value: '75'
                    }
                ],
                toolbar: [
                    'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight',
                    '|',
                    'resizeImage',
                    '|',
                    'imageTextAlternative'
                ]
            },

            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells'
                ]
            },
            mediaEmbed: {
                extraProviders: [
                    //gestion des clips twitch
                    {
                        url: /(.*)clips.twitch.tv(.*)/,
                        name : 'twicht clip',
                        html: (match) => {
                            console.log(match.input)
                            return `
                                <div style="position: relative; padding-bottom: 100%; height: 0; ">
                                    <iframe
                                        src="${match.input}&parent=tartinenews.cf"
                                        style="position: absolute; width: 100%; height: 100%; top: 0; left: 0;"
                                        frameborder="0"
                                        webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>
                                </div>
                            `;
                        }
                    }
                ]
            },
            licenseKey: ''
        }).then((editor) => {
            window.editor = editor;
        }).catch((error) => {
            console.error(error);
        });

        var btn_refreshPreview = document.querySelector('#refreshPreview')
        var div_preview = document.querySelector('#preview')

        var personnage = document.getElementById("personnage")
        var datelive = document.getElementById("datelive")

        var textarea = document.querySelector('#content-form')

        btn_refreshPreview.addEventListener('click', () => {

            div_preview.innerHTML = editor.getData();

            textarea.value = editor.getData();

        })

    </script>


