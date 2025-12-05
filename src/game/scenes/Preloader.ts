import { Scene } from 'phaser';

export class Preloader extends Scene {
    constructor() {
        super('Preloader');
    }

    init() {
        //  We loaded this image in our Boot Scene, so we can display it here
        this.add.image(512, 384, 'background');

        //  A simple progress bar. This is the outline of the bar.
        this.add.rectangle(512, 384, 468, 32).setStrokeStyle(1, 0xffffff);

        //  This is the progress bar itself. It will increase in size from the left based on the % of progress.
        const bar = this.add.rectangle(512 - 230, 384, 4, 28, 0xffffff);

        //  Use the 'progress' event emitted by the LoaderPlugin to update the loading bar
        this.load.on('progress', (progress: number) => {

            //  Update the progress bar (our bar is 464px wide, so 100% = 464px)
            bar.width = 4 + (460 * progress);

        });
    }

    preload() {
        //  Load the assets for the game - Replace with your own assets
        this.load.setPath('assets');

        this.load.image('logo', 'logo.png');
        this.load.image('play_button', 'playbutton.png');

        // Load dialog data
        this.load.json('dialogs', 'data/dialogs.json');

        // Load wizard portraits (Animated)
        this.load.image('madwizard1', 'wizard/madwizard1.png');
        this.load.image('madwizard2', 'wizard/madwizard2.png');

        // Load wizard portraits (Placeholders)
        this.load.image('wizart_cloud', 'https://placehold.co/200x200/550000/ffffff.png?text=Wizart+Cloud');
        this.load.image('wizart_screen', 'https://placehold.co/200x200/550000/ffffff.png?text=Wizart+Screen');
        this.load.image('wizart_firewall', 'https://placehold.co/200x200/550000/ffffff.png?text=Wizart+Firewall');
        this.load.image('wizart_angry', 'https://placehold.co/200x200/ff0000/ffffff.png?text=Wizart+Angry');
        this.load.image('wizart_glitch', 'https://placehold.co/200x200/000000/00ff00.png?text=Wizart+Glitch');

        this.load.image('wizart1', 'wizart/badwizard.png');
        this.load.image('wizart2', 'wizart/badwizard2.png');

        this.load.image('prof1', 'clippy/clippy.png');
        this.load.image('prof2', 'clippy/clippy2.png');
        this.load.image('prof', 'clippy/clippy.png');

        this.load.image('student1', 'student/student.png');
        this.load.image('student2', 'student/student2.png');

        // Load backgrounds
        this.load.image('bg_cimetiere', 'bgcimetiere.png');
        this.load.image('bg_atelier', 'bgclasse.png');
        this.load.image('bg_serveur', 'bgfirewall.png');

        // Load items
        this.load.image('item_usb', 'usb.png');
        this.load.image('item_ssd', 'ssd.png');
        this.load.image('item_tchap', 'https://placehold.co/64x64/ff00ff/ffffff.png?text=Tchap');

        // Projectile for Scene 3 Boss
        this.load.image('projectile', 'https://placehold.co/32x32/ff0000/ffffff.png?text=X');
    }

    create() {
        //  When all the assets have loaded, it's often worth creating global objects here that the rest of the game can use.
        //  For example, you can define global animations here, so we can use them in other scenes.

        //  Move to the MainMenu. You could also swap this for a Scene Transition, such as a camera fade.
        this.scene.start('MainMenu');
    }
}
