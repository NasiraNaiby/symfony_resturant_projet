<?php

namespace App\Controller\Admin;

use App\Entity\Commands;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class CommandsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commands::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('id')->onlyOnIndex(),
            TextField::new('command_etat'),
            DateTimeField::new('command_date')->setFormat('Y-M-d H:i:s'),
            MoneyField::new('total')->setCurrency('EUR'),
            TextField::new('payment_method'),
            TextField::new('user.user_nom', 'User Name')->onlyOnIndex(),
            TextField::new('user.email', 'User Email')->onlyOnIndex(),
        ];
    }

    #[Route('/admin/commands/{id}/confirm', name: 'admin_commands_confirm')]
    public function confirmOrder(EntityManagerInterface $entityManager, int $id, MailerService $mailerService): RedirectResponse
    {
        // Fetch the Commands entity manually
        $command = $entityManager->getRepository(Commands::class)->find($id);

        if (!$command) {
            $this->addFlash('danger', 'Command not found.');
            return $this->redirectToRoute('admin_commands_index');
        }

        $user = $command->getUser();
        if (!$user) {
            $this->addFlash('danger', 'User not found for this order.');
            return $this->redirectToRoute('admin_commands_index');
        }

        // Change the status of the order to 'confirmed'
        $command->setCommandEtat('confirmed');
        $entityManager->flush();

        // Send confirmation email to the user
        $userEmail = $user->getEmail();
        $userName = $user->getUserNom();
        $address = $user->getAddresse();
        $total = $command->getTotal();

        $emailContent = "<p>Dear {$userName},</p>";
        $emailContent .= "<p>Your order has been confirmed and will be delivered to the following address:</p>";
        $emailContent .= "<p><strong>Address:</strong> {$address}</p>";
        $emailContent .= "<p><strong>Total Amount:</strong> €{$total}</p>";
        $emailContent .= "<p>Thank you for your order!</p>";

        $mailerService->sendEmail(
            $userEmail,
            $emailContent,
            'Your Order Confirmation'
        );

        $this->addFlash('success', 'Order successfully confirmed and email sent to the user.');
        return $this->redirectToRoute('admin_commands_index');
    }

    public function configureActions(Actions $actions): Actions
    {
        $confirmOrder = Action::new('confirmOrder', 'Confirm')
            ->linkToRoute('admin_commands_confirm', function (Commands $command): array {
                return ['id' => $command->getId()];
            })
            ->setCssClass('btn btn-success');

        return $actions
            ->add(Crud::PAGE_INDEX, $confirmOrder)
            ->add(Crud::PAGE_DETAIL, $confirmOrder);
    }
}
