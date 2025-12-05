import Phaser from 'phaser';
import { DialogNode } from '../systems/DialogManager';

export class DialogUI extends Phaser.GameObjects.Container {
    private background: Phaser.GameObjects.Rectangle;
    private speakerText: Phaser.GameObjects.Text;
    private dialogText: Phaser.GameObjects.Text;
    private portrait: Phaser.GameObjects.Image;

    public isTyping: boolean = false;
    private fullText: string = '';
    private typingTimer: Phaser.Time.TimerEvent | null = null;
    private portraitTimer: Phaser.Time.TimerEvent | null = null;
    private currentPortraitFrame: number = 1;
    private portraitBaseKey: string = '';

    constructor(scene: Phaser.Scene) {
        super(scene, 0, 0);
        this.scene = scene;

        // Create UI elements
        this.createBackground();
        this.createPortrait();
        this.createTexts();

        // Add to scene and hide initially
        this.scene.add.existing(this);
        this.setVisible(false);
        this.setDepth(100); // Ensure it's on top
    }

    private createBackground() {
        const { width, height } = this.scene.scale;
        const boxHeight = height * 0.25; // 25% of screen height
        const boxWidth = width * 0.9; // 90% of screen width
        const x = width / 2;
        const y = height - (boxHeight / 2) - 20; // 20px padding from bottom

        this.background = this.scene.add.rectangle(x, y, boxWidth, boxHeight, 0x000000, 0.8);
        this.background.setStrokeStyle(2, 0xffffff);
        this.add(this.background);
    }

    private createPortrait() {
        const { width, height } = this.scene.scale;
        const boxHeight = height * 0.25;
        const boxBottom = height - 20;

        // Position portrait on the left side of the box
        const x = (width * 0.05) + (boxHeight * 0.5); // Left margin + half width of portrait area
        const y = boxBottom - (boxHeight * 0.5);

        this.portrait = this.scene.add.image(x, y, '');
        this.portrait.setScale(0.5);
        this.add(this.portrait);
    }

    private createTexts() {
        const { width, height } = this.scene.scale;
        const boxHeight = height * 0.25;
        const boxWidth = width * 0.9;
        const boxTop = height - 20 - boxHeight;
        const boxLeft = (width - boxWidth) / 2;

        // Text starts after the portrait area
        const portraitAreaWidth = boxHeight; // Assume square area for portrait
        const textX = boxLeft + portraitAreaWidth + 20;
        const textY = boxTop + 20;
        const textWidth = boxWidth - portraitAreaWidth - 40;

        this.speakerText = this.scene.add.text(textX, textY, '', {
            fontFamily: 'Arial',
            fontSize: `${Math.max(24, height * 0.03)}px`, // Responsive font size
            color: '#ffff00',
            fontStyle: 'bold'
        });
        this.add(this.speakerText);

        this.dialogText = this.scene.add.text(textX, textY + (height * 0.05), '', {
            fontFamily: 'Arial',
            fontSize: `${Math.max(20, height * 0.025)}px`, // Responsive font size
            color: '#ffffff',
            wordWrap: { width: textWidth }
        });
        this.add(this.dialogText);
    }

    public showDialog(node: DialogNode) {
        this.setVisible(true);

        this.speakerText.setText(node.speaker);
        this.dialogText.setText(''); // Start empty
        this.fullText = node.text;

        if (node.portrait) {
            // Assume portrait key format like "madwizard1", "madwizard2"
            // We'll strip the number to get the base key if needed, or just use the provided key as base
            // For this specific request, we know it toggles between 1 and 2.
            // Let's assume the node.portrait is "madwizard1" or "madwizard"
            this.portraitBaseKey = node.portrait.replace(/[0-9]/g, '');
            // If the key was "madwizard1", base is "madwizard". 
            // If the user provided "madwizard1" in json, we should probably handle that.
            // Actually, the JSON has "madwizard1". Let's try to be smart.
            if (node.portrait.endsWith('1') || node.portrait.endsWith('2')) {
                this.portraitBaseKey = node.portrait.slice(0, -1);
            } else {
                this.portraitBaseKey = node.portrait;
            }

            this.portrait.setTexture(this.portraitBaseKey + '1');
            this.portrait.setVisible(true);
            this.startPortraitAnimation();
        } else {
            this.portrait.setVisible(false);
            this.stopPortraitAnimation();
        }

        this.startTyping();
    }

    private startTyping() {
        this.isTyping = true;
        let currentIndex = 0;

        if (this.typingTimer) {
            this.typingTimer.remove();
        }

        this.typingTimer = this.scene.time.addEvent({
            delay: 50, // Speed of typing
            callback: () => {
                this.dialogText.text += this.fullText[currentIndex];
                currentIndex++;

                if (currentIndex >= this.fullText.length) {
                    this.completeTyping();
                }
            },
            repeat: this.fullText.length - 1
        });
    }

    public completeTyping() {
        if (this.typingTimer) {
            this.typingTimer.remove();
            this.typingTimer = null;
        }
        this.dialogText.setText(this.fullText);
        this.isTyping = false;
        this.stopPortraitAnimation();
        // Ensure we end on frame 1 or a closed mouth frame if we had one, but frame 1 is fine
        if (this.portrait.visible) {
            this.portrait.setTexture(this.portraitBaseKey + '1');
        }
    }

    private startPortraitAnimation() {
        this.stopPortraitAnimation();
        this.currentPortraitFrame = 1;

        this.portraitTimer = this.scene.time.addEvent({
            delay: 150, // Animation speed
            callback: () => {
                this.currentPortraitFrame = this.currentPortraitFrame === 1 ? 2 : 1;
                this.portrait.setTexture(this.portraitBaseKey + this.currentPortraitFrame);
            },
            loop: true
        });
    }

    private stopPortraitAnimation() {
        if (this.portraitTimer) {
            this.portraitTimer.remove();
            this.portraitTimer = null;
        }
    }

    public hideDialog() {
        this.setVisible(false);
        this.stopPortraitAnimation();
        if (this.typingTimer) {
            this.typingTimer.remove();
            this.typingTimer = null;
        }
    }
}
